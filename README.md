# FireBet

FireBet to aplikacja webowa, która umożliwia studentom zakładanie się na wyniki różnych grup wirtualnych banków (V-Banks). Projekt ten został stworzony w celu nauki programowania w PHP oraz zarządzania bazą danych MySQL.

## Funkcjonalności

1. **Rejestracja i logowanie użytkowników**:

    - Użytkownicy mogą się rejestrować, logować oraz wylogowywać.
    - Administratorzy i menedżerowie mają dostęp do panelu zarządzania.

2. **Panel zarządzania**:

    - Administratorzy mogą dodawać nowych użytkowników, V-Banki oraz grupy.
    - Możliwość przypisywania studentów do grup.
    - Zarządzanie zakładami i wynikami V-Banków.

3. **Zakłady**:

    - Studenci mogą przeglądać dostępne V-Banki i grupy.
    - Możliwość stawiania zakładów na wybrane grupy.
    - Wyświetlanie szczegółów zakładów, takich jak kwota, zysk/strata, status oraz współczynnik.

4. **Komentarze i polubienia**:
    - Użytkownicy mogą dodawać komentarze do V-Banków.
    - Możliwość polubienia komentarzy.

## Struktura Bazy Danych
![image](https://github.com/user-attachments/assets/3ffbf4a2-2ebd-4a3f-a6fe-4cf137974468)

Baza danych składa się z następujących tabel:

-   **students**: Przechowuje informacje o studentach, takie jak imię, ocena, opis, rola, email i hasło.
-   **vbanks**: Przechowuje informacje o V-Bankach, takie jak tytuł i data.
-   **groups**: Przechowuje informacje o grupach, takie jak ID V-Banku, ID lidera oraz ocena.
-   **student_group**: Łączy studentów z grupami.
-   **comments**: Przechowuje komentarze dodane przez studentów do V-Banków.
-   **likes**: Przechowuje polubienia komentarzy.
-   **bets**: Przechowuje zakłady studentów, takie jak kwota, zysk/strata, status oraz ID grupy.

## Technologie

-   **PHP**: Główny język programowania używany do tworzenia logiki serwera.
-   **MySQL**: System zarządzania bazą danych.
-   **HTML/CSS**: Używane do tworzenia interfejsu użytkownika.
-   **JavaScript**: Używany do interakcji na stronie, takich jak dynamiczne ładowanie danych.

## Podsumowanie

FireBet to kompleksowy projekt aplikacji webowej, który umożliwia studentom zakładanie się na wyniki grup wirtualnych banków. Projekt ten demonstruje umiejętności programowania w PHP, zarządzania bazą danych MySQL oraz tworzenia interfejsu użytkownika za pomocą HTML, CSS i JavaScript.

## Link do projektu

[Testuj projekt tutaj](http://firegame.pl)
