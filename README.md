# 🎬 Beech Bioscoop Reserveringssysteem

Een modern en elegant bioscoopreserveringssysteem gebouwd met Laravel 11, met real-time stoelkeuze, meerdere stoeltypes en een intuïtieve beheerdersinterface.

![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red)

## ✨ Functionaliteiten

- 🎯 Real-time controle van stoelbeschikbaarheid
- 🪑 Meerdere stoeltypes (Standaard, Luxe, Rolstoel)
- 💫 Mooie, responsieve UI met Tailwind CSS
- 🎨 Modern ontwerp met vloeiende animaties
- 🔐 Veilige beheerdersinterface
- 📱 Mobielvriendelijke interface

## 🚀 Installatie

### Vereisten

- PHP 8.2 of hoger
- Composer
- Node.js & NPM

### Installatiestappen

1. **Kloon de repository**
   ```bash
   git clone https://github.com/Dave-Swagten/Opdracht-Reserveringssysteem-Bioscoop.git
   cd Opdracht-Reserveringssysteem-Bioscoop/reserveringssysteem
   ```

2. **Installeer PHP dependencies**
   ```bash
   composer install
   ```

3. **Installeer JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Omgevingsinstellingen configureren**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Voer migraties en seeders uit**
   ```bash
   php artisan migrate --seed
   ```

6. **Bouw de assets**
   ```bash
   npm run build
   ```

7. **Start de ontwikkelserver**
   ```bash
   php artisan serve
   ```

## 🎯 Aan de slag

1. Bezoek `http://127.0.0.1:8000` om toegang te krijgen tot de bioscoopboekingsinterface.
2. Open het beheerderspaneel op `http://127.0.0.1:8000/admin/login`.

### Beheerdersinloggegevens
```
E-mail: admin@beechbioscoop.nl
Wachtwoord: admin
```

## 🏗️ Architectuur

Het systeem is opgebouwd met verschillende ontwerppatronen om onderhoudbaarheid en schaalbaarheid te garanderen:

- **Factory Pattern**: Voor het aanmaken van verschillende soorten stoelen
- **Singleton Pattern**: Voor databasebeheer
- **MVC-architectuur**: Duidelijke scheiding van verantwoordelijkheden
- **Repository Pattern**: Abstractie van data-toegang

---
Gemaakt met ☕ voor Beech Bioscoop

