# Documentazione API: Gestione dei numeri di serie di dispositivi e parti associate

## Introduzione
La documentazione descrive la progettazione delle API RESTful per gestire il processo di registrazione di dispositivi
elettronici e delle sue parti durante il processo produttivo di un'azienda.
Ogni dispositivo è identificato da un numero di serie e ha uno stato che può essere "In lavorazione",
"Testato", o "Pronto". 
È possibile aggiungere più parti (schede elettroniche) al dispositivo o rimuoverle.
Inoltre è possibile registrare un test di una parte e ottenere le informazioni relative a un dispositivo e a tutte le 
sue parti associate.

## Endpoints API

### Creare un nuovo dispositivo

#### Endpoint
```php
POST /device/create
```
##### Descrizione
Crea un nuovo dispositivo assegnandogli un numero di serie unico seguendo le logiche di business

**Parametri di Input**: -

**Risposta di Output**
+ 201: New device with serial number `serialNumber` succesfully created
+ 500: Error during creation of new device. Error: `$e->getMessage()`

**Esempio di Chiamata in PHP**
```php
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, '{base_url}/api/device/create');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);
```

### Generazione del numero di serie del dispositivo

Il numero di serie è chiave della tabella Device e questo viene generato automaticamente secondo la logica di contenere
`DEV` seguito da un numero di due cifre nell'intervallo [00, 99].

#### Logica di Generazione del Numero di Serie

La logica che si occupa di creare il numero di serie è contenuta nella funzione `generateDeviceSerialNumber`.
Questa genera il numero di serie del nuovo dispositivo seguendo i seguenti passaggi:
+ Recupera dal database il numero di serie dell'ultimo dispositivo inserito
  + Se non ci sono record a DB viene restituito il valore iniziale `DEV00`
+ Estrae l'ultimo numero progressivo di due cifre dal numero di serie
  + Se il numero è `99` lancia un'eccezione poiché è stato raggiunto il numero massimo di numeri di serie disponibili
  + Altrimenti incrementa di uno il numero
+ Formatta il nuovo numero di serie incrementato per rispettare il formato `DEVxx`

************

### Aggiunta di una parte a un dispositivo

#### Endpoint
```php
POST /device/{device_serial_number}/add-part
```

##### Descrizione
Aggiunge una parte (scheda elettronica) a un dispositivo esistente.
**Una parte viene aggiunta se e solo se l'ultimo test effettuato su di essa ha avuto esito positivo**

**Parametri di Input**: 
+ `device_serial_number` - Il numero di serie del dispositivo a cui si vuole aggiungere la parte
+ `part_serial_number` - Il numero di serie della parte da aggiungere

**Risposta di Output**
+ 201: Part succesfully addedd to device with serial number: `device_serial_number`
+ 400: Part is not available. You can't use it for device
+ 400: Part has not passed last test. You can't use it for device
+ 500: Error while adding the part `part_serial_number` to the device `device_serial_number` Error: `$e->getMessage()`

**Esempio di Chiamata in PHP**
```php
$curl = curl_init();
$data = ['part_serial_number' => 'PN12345678'];
curl_setopt($curl, CURLOPT_URL, '{base_url}/api/device/DEV01/add-part');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);
```

************

### Rimozione di una parte da un dispositivo

#### Endpoint
```php
DELETE /part/{part_serial_number}/remove-from-device
```

##### Descrizione
Permette di rimuove una parte da un dispositivo.

**Parametri di Input**:
+ `part_serial_number` - Il numero di serie della parte da rimuovere dal dispositivo

**Risposta di Output**
+ 200: Part removed successfully from device
+ 500: Error removing part from device. Part serial number: `part_serial_number` Error: `$e->getMessage()`

**Esempio di Chiamata in PHP**
```php
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, '{base_url}/api/part/PN12345678/remove-from-device');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);
```

************

### Aggiunta di un test a una parte

#### Endpoint
```php
POST /part/{part_serial_number}/add-test
```

##### Descrizione
Registra l'esito di un test effettuato sulla parte

**Parametri di Input**:
+ `part_serial_number` - Il numero di serie della parte per la quale si registra il test

**Risposta di Output**
+ 200: Test correctly added to part
+ 500: Error while adding the test for part `part_serial_number` 

**Esempio di Chiamata in PHP**
```php
POST /device/create
PHP cURL Example
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://<host>/api.php/device/create');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
```


************

### Informazioni di un dispositivo e delle sue parti associate

#### Endpoint
```php
GET /device/{device_part_number}
```

##### Descrizione
Questo endpoint permette di ottenere i dettagli su un dispositivo specificato. Nel dettadlio include il suo numero
di serie, il suo stato e i dettagli di tutte le sue parti associate.

**Parametri di Input**:
+ `device_part_number` - Il numero di parte del dispositivo di cui si desiderano ottenere le informazioni

**Risposta di Output**
+ La risposta è un oggetto JSON contenente i dettagli del dispositivo richiesto.

```json
{
    "Device_Serial_Number": "DEV123",
    "Device_Status": "In lavorazione",
    "Parts_Info": [
        {
            "ID": "1",
            "Serial_Number": "SN001",
            "Component_ID": "C001",
            "Device_SN": "DEV123"
        },
        {
            "ID": "2",
            "Serial_Number": "SN002",
            "Component_ID": "C002",
            "Device_SN": "DEV123"
        }
    ]
}
```

#### Descrizione dei campi nella risposta

- `Device_Serial_Number`: Numero di serie del dispositivo
- `Device_Status`: Stato attuale del dispositivo, che può essere "In lavorazione", "Testato" o "Pronto"
- `Parts_Info`: Array contenente informazioni sulle parti associate al dispositivo che comprendono:
    - `ID`: Identificativo unico della parte
    - `Serial_Number`: Numero di serie della parte
    - `Component_ID`: Identificativo del tipo di componente
    - `Device_SN`: Numero di serie del dispositivo

    
