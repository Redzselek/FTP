# Angular Authentication Tutorial with Laravel Sanctum

Ez a tutorial bemutatja, hogyan kell helyesen kezelni a bejelentkezést és kijelentkezést egy Angular alkalmazásban, amely Laravel Sanctum API-t használ a háttérben.

## 1. Előfeltételek

- Angular alkalmazás
- HttpClientModule importálva az AppModule-ban
- Laravel backend Sanctum hitelesítéssel

## 2. Authentication Service létrehozása

Először hozzunk létre egy szolgáltatást az autentikáció kezelésére:

```bash
ng generate service services/auth
```

## 3. Auth Service implementálása

```typescript
// src/app/services/auth.service.ts
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable, throwError } from 'rxjs';
import { catchError, tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost:8000'; // Módosítsd a backend URL-re
  private userSubject = new BehaviorSubject<any>(null);
  public user$ = this.userSubject.asObservable();
  
  constructor(private http: HttpClient) {
    // Ellenőrizzük, hogy van-e bejelentkezett felhasználó induláskor
    this.checkAuthStatus();
  }

  /**
   * Bejelentkezés
   * Fontos: withCredentials: true beállítás szükséges a sütik kezeléséhez
   */
  login(email: string, password: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/vizsga/login`, { email, password }, {
      withCredentials: true // Ez kritikus a süti kezeléséhez!
    }).pipe(
      tap((response: any) => {
        if (response.status === 'success') {
          this.userSubject.next({ isLoggedIn: true });
          // Opcionálisan tárolhatunk felhasználói adatokat is
          this.getUserInfo();
        }
      }),
      catchError(error => {
        console.error('Bejelentkezési hiba:', error);
        return throwError(() => error);
      })
    );
  }

  /**
   * Kijelentkezés
   * Fontos: withCredentials: true beállítás szükséges a sütik törléséhez
   */
  logout(): Observable<any> {
    return this.http.post(`${this.apiUrl}/vizsga/logout`, {}, {
      withCredentials: true // Ez kritikus a süti törléséhez!
    }).pipe(
      tap(() => {
        this.userSubject.next(null);
      }),
      catchError(error => {
        console.error('Kijelentkezési hiba:', error);
        return throwError(() => error);
      })
    );
  }

  /**
   * Felhasználói adatok lekérése
   */
  getUserInfo(): Observable<any> {
    return this.http.get(`${this.apiUrl}/vizsga/user`, {
      withCredentials: true
    }).pipe(
      tap(user => {
        this.userSubject.next({ isLoggedIn: true, ...user });
      }),
      catchError(error => {
        console.error('Felhasználói adatok lekérési hiba:', error);
        return throwError(() => error);
      })
    );
  }

  /**
   * Ellenőrzi, hogy a felhasználó be van-e jelentkezve
   */
  private checkAuthStatus(): void {
    this.http.get(`${this.apiUrl}/vizsga/user`, {
      withCredentials: true
    }).subscribe({
      next: (user) => {
        this.userSubject.next({ isLoggedIn: true, ...user });
      },
      error: () => {
        this.userSubject.next(null);
      }
    });
  }

  /**
   * Visszaadja, hogy a felhasználó be van-e jelentkezve
   */
  isLoggedIn(): boolean {
    return this.userSubject.value !== null;
  }
}
```

## 4. HTTP Interceptor létrehozása (opcionális, de ajánlott)

Az interceptor segít a kérések automatikus kezelésében:

```bash
ng generate interceptor interceptors/auth
```

```typescript
// src/app/interceptors/auth.interceptor.ts
import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpErrorResponse
} from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Router } from '@angular/router';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(private router: Router) {}

  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    // Minden kéréshez hozzáadjuk a withCredentials: true beállítást
    request = request.clone({
      withCredentials: true
    });

    return next.handle(request).pipe(
      catchError((error: HttpErrorResponse) => {
        // Ha 401-es hibát kapunk, akkor átirányítjuk a felhasználót a bejelentkezési oldalra
        if (error.status === 401) {
          this.router.navigate(['/login']);
        }
        return throwError(() => error);
      })
    );
  }
}
```

Regisztráljuk az interceptort az AppModule-ban:

```typescript
// src/app/app.module.ts
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './interceptors/auth.interceptor';

@NgModule({
  // ...
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true }
  ],
  // ...
})
export class AppModule { }
```

## 5. Login Component létrehozása

```bash
ng generate component components/login
```

```typescript
// src/app/components/login/login.component.ts
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  loginForm: FormGroup;
  errorMessage: string = '';
  isLoading: boolean = false;

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router
  ) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required]
    });
  }

  onSubmit(): void {
    if (this.loginForm.invalid) {
      return;
    }

    this.isLoading = true;
    this.errorMessage = '';

    const { email, password } = this.loginForm.value;

    this.authService.login(email, password).subscribe({
      next: (response) => {
        this.isLoading = false;
        if (response.status === 'success') {
          this.router.navigate(['/dashboard']);
        }
      },
      error: (error) => {
        this.isLoading = false;
        this.errorMessage = error.error?.message || 'Hiba történt a bejelentkezés során.';
      }
    });
  }
}
```

```html
<!-- src/app/components/login/login.component.html -->
<div class="login-container">
  <h2>Bejelentkezés</h2>
  
  <form [formGroup]="loginForm" (ngSubmit)="onSubmit()">
    <div class="form-group">
      <label for="email">Email</label>
      <input 
        type="email" 
        id="email" 
        formControlName="email" 
        class="form-control"
        [ngClass]="{'is-invalid': loginForm.get('email')?.invalid && loginForm.get('email')?.touched}"
      >
      <div *ngIf="loginForm.get('email')?.invalid && loginForm.get('email')?.touched" class="invalid-feedback">
        <div *ngIf="loginForm.get('email')?.errors?.['required']">Email cím megadása kötelező</div>
        <div *ngIf="loginForm.get('email')?.errors?.['email']">Érvénytelen email formátum</div>
      </div>
    </div>
    
    <div class="form-group">
      <label for="password">Jelszó</label>
      <input 
        type="password" 
        id="password" 
        formControlName="password" 
        class="form-control"
        [ngClass]="{'is-invalid': loginForm.get('password')?.invalid && loginForm.get('password')?.touched}"
      >
      <div *ngIf="loginForm.get('password')?.invalid && loginForm.get('password')?.touched" class="invalid-feedback">
        <div *ngIf="loginForm.get('password')?.errors?.['required']">Jelszó megadása kötelező</div>
      </div>
    </div>
    
    <div *ngIf="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>
    
    <button 
      type="submit" 
      class="btn btn-primary" 
      [disabled]="loginForm.invalid || isLoading"
    >
      <span *ngIf="isLoading" class="spinner-border spinner-border-sm mr-1"></span>
      Bejelentkezés
    </button>
  </form>
</div>
```

## 6. Kijelentkezés gomb implementálása

```typescript
// src/app/components/header/header.component.ts
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent {
  isLoggedIn$ = this.authService.user$;

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  logout(): void {
    this.authService.logout().subscribe({
      next: () => {
        this.router.navigate(['/login']);
      },
      error: (error) => {
        console.error('Kijelentkezési hiba:', error);
      }
    });
  }
}
```

```html
<!-- src/app/components/header/header.component.html -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" routerLink="/">Alkalmazás</a>
    
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" routerLink="/" routerLinkActive="active" [routerLinkActiveOptions]="{exact: true}">Főoldal</a>
        </li>
        <li class="nav-item" *ngIf="(isLoggedIn$ | async)?.isLoggedIn">
          <a class="nav-link" routerLink="/dashboard" routerLinkActive="active">Dashboard</a>
        </li>
      </ul>
      
      <ul class="navbar-nav ml-auto">
        <ng-container *ngIf="(isLoggedIn$ | async)?.isLoggedIn; else loginLink">
          <li class="nav-item">
            <a class="nav-link" (click)="logout()" style="cursor: pointer;">Kijelentkezés</a>
          </li>
        </ng-container>
        <ng-template #loginLink>
          <li class="nav-item">
            <a class="nav-link" routerLink="/login" routerLinkActive="active">Bejelentkezés</a>
          </li>
        </ng-template>
      </ul>
    </div>
  </div>
</nav>
```

## 7. Auth Guard létrehozása a védett útvonalakhoz

```bash
ng generate guard guards/auth
```

```typescript
// src/app/guards/auth.guard.ts
import { Injectable } from '@angular/core';
import { CanActivate, Router, UrlTree } from '@angular/router';
import { Observable, map, take } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {
  
  constructor(private authService: AuthService, private router: Router) {}
  
  canActivate(): Observable<boolean | UrlTree> {
    return this.authService.user$.pipe(
      take(1),
      map(user => {
        const isLoggedIn = !!user?.isLoggedIn;
        if (isLoggedIn) {
          return true;
        }
        
        // Átirányítás a bejelentkezési oldalra
        return this.router.createUrlTree(['/login']);
      })
    );
  }
}
```

## 8. Útvonalak beállítása

```typescript
// src/app/app-routing.module.ts
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { HomeComponent } from './components/home/home.component';
import { AuthGuard } from './guards/auth.guard';

const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'login', component: LoginComponent },
  { path: 'dashboard', component: DashboardComponent, canActivate: [AuthGuard] },
  // Egyéb védett útvonalak
  { path: '**', redirectTo: '' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
```

## 9. CORS beállítása a Laravel oldalon

A Laravel oldalon ellenőrizd a CORS beállításokat a `config/cors.php` fájlban:

```php
return [
    'paths' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:4200'], // Az Angular alkalmazás URL-je
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Ez kritikus a sütik működéséhez!
];
```

## 10. Hibaelhárítás

### Probléma: A süti nem törlődik kijelentkezéskor

1. Ellenőrizd, hogy a `withCredentials: true` be van-e állítva minden HTTP kérésben
2. Ellenőrizd, hogy a Laravel oldalon a `supports_credentials` értéke `true` a CORS beállításokban
3. Ellenőrizd, hogy a süti beállításai megfelelőek-e (domain, path, secure, httpOnly)

### Probléma: CORS hibák

1. Ellenőrizd, hogy a Laravel oldalon a CORS beállításokban szerepel-e az Angular alkalmazás URL-je
2. Ellenőrizd, hogy a `supports_credentials` értéke `true` a CORS beállításokban
3. Ellenőrizd, hogy az Angular oldalon a `withCredentials: true` be van-e állítva minden HTTP kérésben

### Probléma: A bejelentkezés sikeres, de a felhasználó nem marad bejelentkezve

1. Ellenőrizd, hogy a süti beállításai megfelelőek-e (lejárati idő, domain, path)
2. Ellenőrizd, hogy az Angular alkalmazás megfelelően kezeli-e a sütiket
3. Ellenőrizd, hogy a Laravel oldalon a Sanctum megfelelően van-e beállítva

## 11. Összefoglalás

A sikeres Angular-Laravel Sanctum autentikáció kulcsa:

1. `withCredentials: true` beállítás minden HTTP kérésben
2. CORS megfelelő beállítása a Laravel oldalon
3. Süti kezelés megfelelő implementálása mindkét oldalon
4. Auth service és interceptor használata az Angular oldalon

Ezekkel a beállításokkal és implementációkkal a bejelentkezés és kijelentkezés megfelelően fog működni az Angular alkalmazásban.
