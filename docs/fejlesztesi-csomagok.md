# Fejlesztési csomag javaslatok – HSWLP:NAS

## Kiinduló állapot összegzése

- A projekt már tartalmazza az elsődleges adatbázis-sémát a megosztásokhoz, konténeres alkalmazásokhoz és audit naplózáshoz, de ezekhez még nem tartoznak Eloquent modellek vagy üzleti logika. A `shares`, `share_permissions`, `apps`, `app_instances` és `audit_log` táblák migrációja előkészítve van, de nincs rájuk épülő réteg (`app/Models` csak a felhasználót definiálja).
- A Filament admin panel be van kötve, azonban a hozzá tartozó resource-ok (Share, SharePermission, App, AppInstance, AuditLog) csak üres űrlap- és tábladefiníciókkal rendelkeznek, így még nem nyújtanak funkcionalitást.
- A Jetstream által biztosított hitelesítés és profil-kezelés elérhető, viszont további NAS-specifikus modul még nem épül rá.

A következő csomagok egymásra épülve segítenek eljutni a teljes Unifi-szerű NAS élményhez.

## Ajánlott fejlesztési csomagok

### 1. csomag – Alap infrastruktúra és domain-modellek
- **Cél:** stabil adatmodell és backend API-k a magfunkciókhoz.
- **Feladatok:**
  - Eloquent modellek és kapcsolatok létrehozása a meglévő táblákhoz (Share, SharePermission, App, AppInstance, AuditLog).
  - Repository/service réteg kialakítása a táblák köré (pl. megosztás létrehozás, jogosultságok kezelése, app életciklus).
  - REST API és Filament resource-ok feltöltése alap mezőkkel, validációkkal.
  - Alap eseménynaplózás (`AuditLog`) bekötése minden fontos művelethez.
- **Előfeltétel:** Laravel alapok rendelkezésre állnak; nincs további függőség.
- **Mérőszám:** sikeres integrációs tesztek az alap CRUD műveletekre, audit bejegyzések automatikus létrejötte.

### 2. csomag – Lemez- és fájlrendszer-kezelés
- **Cél:** lokális vagy távoli háttértárak felderítése, csatolása és kvótakezelése.
- **Feladatok:**
  - `disk` szolgáltatás réteg kialakítása (pl. ZFS, Btrfs, LVM moduláris adapterei).
  - Lemezállapot lekérdezése, SMART adatok, valamint kvóta- és használati mutatók gyűjtése.
  - Megosztások létrehozásakor automatikus könyvtár-hozzárendelés (`shares.path`) és kvóta beállítás.
  - Háttérfeladatok (queue + scheduler) létrehozása állapotfrissítésre.
- **Előfeltétel:** 1. csomag domain modelljei.
- **Mérőszám:** legalább egy fájlrendszer-adapter működőképes, időzített állapotfrissítés és kvótariport készül.

### 3. csomag – Megosztások és fájlkezelő UI
- **Cél:** modern, Unifi stílusú felület a megosztások kezelésére és a fájlok böngészésére.
- **Feladatok:**
  - Filament/Livewire komponensek felépítése a megosztások listázására, létrehozására, jogosultság kezelésére.
  - Webes fájlkezelő modul (hierarchikus lista, drag & drop, gyors megosztás-link generálás).
  - ACL-k integrálása a frontend oldalon (`share_permissions` alapján).
  - SMB/NFS export állapot megjelenítése, szolgáltatás vezérlő gombok.
- **Előfeltétel:** 1–2. csomag.
- **Mérőszám:** felhasználó UI-ból tud megosztást létrehozni, felhasználót hozzárendelni, fájlt feltölteni és letölteni.

### 4. csomag – Konténeres alkalmazás-katalógus
- **Cél:** Docker alapú appok telepítése, konfigurálása és felügyelete.
- **Feladatok:**
  - Alkalmazáskatalógus JSON/YAML séma definiálása (`apps` tábla feltöltése seederekkel).
  - Docker Compose vagy Podman wrapper service implementálása (`app_instances` kezelése).
  - Környezeti változók, volume binding és hálózati portok dinamikus konfigurálása.
  - Indítás/leállítás/újratelepítés vezérlés, állapot polling és napló-hozzáférés.
- **Előfeltétel:** 1. csomag.
- **Mérőszám:** legalább egy példaalkalmazás (pl. Jellyfin) sikeres telepítése, állapot-visszajelzés az admin felületen.

### 5. csomag – Biztonság, jogosultság és auditálás
- **Cél:** vállalati szintű hozzáférés-szabályozás és nyomon követhetőség.
- **Feladatok:**
  - RBAC szerepkörök kidolgozása (admin, power user, read-only stb.).
  - Kétfaktoros hitelesítés kötelezővé tétele kritikus műveletekhez.
  - Audit napló részletezése (régi és új értékek, IP-cím, kliens, app_instance események).
  - Riasztási rendszer bevezetése (e-mail/webhook) gyanús eseményekre.
- **Előfeltétel:** 1. csomag audit log alapjai.
- **Mérőszám:** jogosultsági mátrix végigtesztelve, audit logból visszakereshető minden admin művelet.

### 6. csomag – Mentés, visszaállítás és replikáció
- **Cél:** adatbiztonság és katasztrófa-elhárítás biztosítása.
- **Feladatok:**
  - Pillanatkép kezelés (ZFS/Btrfs snapshot), verziózás integrálása a megosztásokhoz.
  - Ütemezhető mentések külső célokra (másik NAS, S3-kompatibilis tárhely).
  - Alkalmazás-konfigurációk export/import folyamata.
  - Visszaállító varázsló, státusz monitorozás.
- **Előfeltétel:** 2. csomag lemezadapterei.
- **Mérőszám:** sikeres mentés-visszaállítás teszt, felhasználói értesítés kész állapotról.

### 7. csomag – Megfigyelhetőség és automatizmusok
- **Cél:** átlátható üzemeltetés, proaktív beavatkozás.
- **Feladatok:**
  - Telemetria modul (Prometheus exporterek, Grafana dashboardok) a lemez, hálózat, konténer metrikákhoz.
  - Esemény alapú automatizmusok (pl. új lemez csatlakozásakor varázsló indítása, alacsony tárhely figyelmeztetés).
  - Webhook/API integrációk más HSWLP komponensekkel.
  - Mobil push értesítések és webes real-time állapot kijelzés (Laravel Echo / WebSockets).
- **Előfeltétel:** 1–4. csomag.
- **Mérőszám:** metrikák gyűjtése grafikonon megjeleníthető, automatizmus eseményre reagál.

---

A fenti csomagokkal jól skálázható ütemterv készíthető: először a backend stabilizálása, majd a NAS-specifikus szolgáltatások fokozatos ráépítése és végül az üzemeltetést segítő képességek kidolgozása.
