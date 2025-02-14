# API Dokumentáció

Ez a dokumentáció tartalmazza az összes elérhető API végpontot és azok használatát.

## Autentikáció nélküli végpontok

### Bejelentkezés
- **URL:** `/vizsga/login`
- **Metódus:** POST
- **Leírás:** Felhasználó bejelentkezése
- **Szükséges adatok:**
  - email
  - password

### Regisztráció
- **URL:** `/vizsga/register`
- **Metódus:** POST
- **Leírás:** Új felhasználó regisztrálása
- **Szükséges adatok:**
  - name
  - email
  - password

### Elfelejtett jelszó
- **URL:** `/vizsga/forgot-password`
- **Metódus:** POST
- **Leírás:** Elfelejtett jelszó esetén új jelszó kérése
- **Szükséges adatok:**
  - email

### Filmek lekérdezése
- **URL:** `/vizsga/movies`
- **Metódus:** GET
- **Leírás:** Összes film lekérdezése

### Sorozatok lekérdezése
- **URL:** `/vizsga/series`
- **Metódus:** GET
- **Leírás:** Összes sorozat lekérdezése

## Védett végpontok (Bejelentkezés szükséges)

> **Fontos:** Ezekhez a végpontokhoz szükséges a bejelentkezéskor kapott token elküldése a kérés fejlécében:
> ```
> Authorization: Bearer {token}
> ```

### Dashboard adatok
- **URL:** `/vizsga/dashboard`
- **Metódus:** POST
- **Leírás:** Dashboard adatok lekérése

### Kijelentkezés
- **URL:** `/vizsga/logout`
- **Metódus:** POST
- **Leírás:** Felhasználó kijelentkeztetése

### Jelszó módosítás
- **URL:** `/vizsga/profile/password`
- **Metódus:** POST
- **Leírás:** Felhasználó jelszavának módosítása
- **Szükséges adatok:**
  - current_password
  - new_password
  - new_password_confirmation

### Fiók törlése
- **URL:** `/vizsga/profile/delete`
- **Metódus:** POST
- **Leírás:** Felhasználói fiók törlése

### Tartalom feltöltése
- **URL:** `/vizsga/upload`
- **Metódus:** POST
- **Leírás:** Új film/sorozat feltöltése

### Feltöltések megtekintése
- **URL:** `/vizsga/feltoltesek`
- **Metódus:** POST
- **Leírás:** Felhasználó által feltöltött tartalmak lekérdezése

### Tartalom szerkesztése
- **URL:** `/vizsga/show/edit`
- **Metódus:** POST
- **Leírás:** Film/sorozat adatainak módosítása

### Tartalom törlése
- **URL:** `/vizsga/show/delete`
- **Metódus:** POST
- **Leírás:** Film/sorozat törlése

### Watchlist kezelése
- **URL:** `/vizsga/watchlist`
- **Metódus:** POST
- **Leírás:** Felhasználó watchlist-jének kezelése

## Hibakezelés

Minden végpont JSON formátumban válaszol. Sikeres kérés esetén 200-as státuszkóddal, hiba esetén pedig a megfelelő hibakóddal és hibaüzenettel.

Példa sikeres válaszra:
```json
{
    "status": "success",
    "data": { ... }
}
```

Példa hibaüzenetre:
```json
{
    "status": "error",
    "message": "A hiba leírása"
}
```
