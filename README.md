# AutoFirma Plugin para FilamentPHP

Plugin de [FilamentPHP](https://filamentphp.com) que integra [AutoFirma](https://firmaelectronica.gob.es/Home/Descargas.html) (la aplicación de firma electrónica del Gobierno de España) en paneles de administración Laravel/Filament.

Permite lanzar el proceso de firma desde una `Action` de Filament, recibir la firma en base64 y procesarla en el servidor.

---

## Requisitos

- PHP ^8.4
- Laravel ^12.0 o ^13.0
- Filament ^5.0
- AutoFirma instalado en el equipo del usuario final

---

## Instalación

```bash
composer require emiliosh/autofirma-plugin
php artisan filament:assets
```

Publica la configuración (opcional):

```bash
php artisan vendor:publish --tag=autofirma-plugin-config
```

---

## Registro del plugin

Registra el plugin en tu `PanelProvider`:

```php
use Emiliosh\AutofirmaPlugin\AutofirmaPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(
            AutofirmaPlugin::make()
        );
}
```

### Opciones de configuración del plugin

```php
AutofirmaPlugin::make()
    ->algorithm('SHA512withRSA')   // Algoritmo de firma (por defecto SHA512withRSA)
    ->signatureFormat('PAdES')     // Formato: XAdES | CAdES | PAdES (por defecto PAdES)
    ->verifySignature(true)        // Verificar la firma en servidor (por defecto true)
    ->useLocalService()            // Usar servicio local en lugar del protocolo afirma://
    ->localServicePort(51234)      // Puerto del servicio local (por defecto 51234)
```

---

## Uso: AutofirmaAction

Añade la acción en cualquier `Resource`, `Page` o `Table` de Filament:

```php
use Emiliosh\AutofirmaPlugin\Actions\AutofirmaAction;

AutofirmaAction::make()
    ->dataToSign(fn ($record) => file_get_contents(storage_path("docs/{$record->id}.pdf")))
    ->afterSigned(function (string $signatureB64, $record) {
        $record->update([
            'signature' => $signatureB64,
            'signed_at' => now(),
        ]);
    })
```

### Parámetros de AutofirmaAction

| Método | Descripción |
|---|---|
| `dataToSign(string\|Closure)` | Datos a firmar. Puede ser un string o un Closure que recibe el `$record` y devuelve el contenido a firmar. |
| `afterSigned(Closure)` | Callback ejecutado tras una firma exitosa. Recibe `(string $signatureB64, mixed $record)`. |
| `onSignError(Closure)` | Callback ejecutado cuando AutoFirma reporta un error. Recibe `(string $errorMessage, mixed $record)`. |

---

## Configuración mediante variables de entorno

```dotenv
AUTOFIRMA_ALGORITHM=SHA512withRSA
AUTOFIRMA_FORMAT=PAdES
AUTOFIRMA_LOCAL_SERVICE=false
AUTOFIRMA_LOCAL_PORT=51234
AUTOFIRMA_VERIFY=true
```

---

## Formatos de firma soportados

| Formato | Uso recomendado |
|---|---|
| `PAdES` | Documentos PDF |
| `XAdES` | Documentos XML / uso general |
| `CAdES` | Ficheros binarios / CMS |

---

## Facade

El paquete registra automáticamente la facade `Autofirma`:

```php
use Emiliosh\AutofirmaPlugin\Facades\Autofirma;

// Preparar datos para enviar a AutoFirma
$dataB64 = Autofirma::prepareData($pdfContent);

// Decodificar la firma recibida
$signatureBytes = Autofirma::decodeSignature($signatureB64);
```

---

