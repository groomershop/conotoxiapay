# Cinkciarz Pay - moduł płatności dla Magento 2
Moduł dostarczający bramkę płatności Cinkciarz Pay dla Magento 2.

## Dziennik zmian
- 1.12.0 Ukryto opcję wyboru akcji płatniczej.
- 1.10.0 Dodano możliwość ukrycia ikony na liście wyboru płatności.
- 1.9.0 Dodano możliwość wyboru dodatkowych ikon metod płatności.
- 1.8.0 Zaktualizowano plugin w celu zapewnienia kompatybilności z Magento 2.4.4 i PHP 8.1.
- 1.4.0 Dodano opcję wyboru widocznych ikon metod płatności na ekranie wyboru metody płatności.
- 1.2.0 Dodano wsparcie dla statusu CANCELLED dla zwrotów.

## Spis treści

* [Wymagania](#wymagania)
* [Instalacja](#instalacja)
* [Konfiguracja](#konfiguracja)
    * [Konfiguracja modułu w Magento 2](#konfiguracja-modułu-w-magento-2)
    * [Konfiguracja punktu płatności w Panelu Sprzedawcy](#konfiguracja-punktu-płatności-w-panelu-sprzedawcy)
* [Zwroty](#zwroty)

## Wymagania
* Magento 2.4.4
* PHP 8.1
* Rozszerzenia PHP:
    * curl
    * json
    * openssl
    * readline

## Instalacja
1. Moduł należy pobrać ze strony [cinkciarz.pl](https://cinkciarz.pl/platnosci/dla-developerow).
2. Należy rozpakować archiwum i skopiować katalog `Conotoxia` do `app/code/` w instancji Magento 2.
3. Należy zalogować się do serwera ze sklepem Magento przez SSH.
4. Następnie należy przejść do katalogu instalacyjnego Magento.
5. Należy włączyć moduł:
    - `php bin/magento module:enable Conotoxia_Pay`.
6. Aktualizacja bazy danych:
    - `php bin/magento setup:upgrade`.
7. Kompilacja kodu i wstrzykiwanie zależności:
    - `php bin/magento setup:di:compile`.
8. Wdrożenie statycznych plików widoków (tylko w trybie produkcyjnym):
    - `php bin/magento setup:static-content:deploy`.
9. Należy sprawdzić uprawnienia do katalogów i plików oraz ustawić je poprawnie, jeśli to konieczne.

## Konfiguracja
Poniższa instrukcja zakłada, że Partner zakończył konfigurację konta w [Panelu Sprzedawcy](https://fx.cinkciarz.pl/merchant).

### Konfiguracja modułu w Magento 2
1. W `Sklepy -> Konfiguracja` należy wybrać `Sprzedaż -> Metody płatności`.
2. W sekcji `INNE METODY PŁATNOŚCI` należy odszukać `Cinkciarz Pay`, a następnie kliknąć `Konfiguruj`.
3. W konfiguracji modułu należy wprowadzić niezbędne dane:
    - `Identyfikator klienta API*` oraz `Hasło klienta API*` - dane dostępowe można wygenerować 
    w [Panelu Sprzedawcy](https://fx.cinkciarz.pl/merchant/configuration) (sekcja `Dane dostępowe`).
    - `Identyfikator punktu płatności` - identyfikator utworzonego punktu sprzedaży
    z poziomu [Panelu Sprzedawcy](https://fx.cinkciarz.pl/merchant).
    - `Klucz prywatny` - istnieje możliwość wygenerowania klucza prywatnego na stronie konfiguracyjnej modułu. Na 
    podstawie klucza prywatnego wprowadzonego na stronie konfiguracji modułu generowany jest klucz publiczny. Klucz ten
    jest automatycznie przesyłany do Cinkciarz Pay po zapisaniu konfiguracji. Nie jest konieczne wprowadzanie klucza
    w [Panelu Sprzedawcy](https://fx.cinkciarz.pl/merchant/public-keys/add?context=configuration). Dodatkowe instrukcje
    dotyczące generowania kluczy można znaleźć w [dokumentacji](https://docs.cinkciarz.pl/platnosci/sklepy-online#generowanie-klucza-publicznego).
    - `Kolejność sortowania` - porządek sortowania w liście metod płatności na stronie realizacji zamówienia.
4. Należy wybrać widoczne ikony metod płatnosci na ekranie wyboru metody płatności.
5. Należy włączyć moduł Cinkciarz Pay.
6. Ostatecznie należy zapisać konfigurację.

`*` Dane można pozyskać przechodząc przez kreator w [Panelu Sprzedawcy](https://fx.cinkciarz.pl/merchant).

### Konfiguracja punktu płatności w [Panelu Sprzedawcy](https://fx.cinkciarz.pl/merchant)
Punkt sprzedaży powinien być skonfigurowany zgodnie z poniższą konfiguracją:

- `Adres powiadomienia o utworzeniu płatności`  
 np. https://magento.store.pl/conotoxia_pay/receive/notifications
  
- `Adres powiadomienia o utworzeniu zwrotu`  
 np. https://magento.store.pl/conotoxia_pay/receive/notifications
  
- `Adres strony dla płatności udanej`  
 np. https://magento.store.pl/checkout/onepage/success
  
- `Adres strony dla płatności nieudanej`  
 np. https://magento.store.pl/checkout/onepage/success

W miejsce `magento.store.pl` należy wstawić domenę sklepu Magento.

## Zwroty
Zwroty można zlecać z poziomu modułu oraz z poziomu [Panelu Sprzedawcy](https://fx.cinkciarz.pl/merchant).

## Akcje płatnicze
Domyślnie wtyczka działa w trybie `Authorize`.  
Więcej informacji w [dokumentacji](https://docs.magento.com/user-guide/configuration/sales/payment-methods.html#payment-actions).
