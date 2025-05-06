# Carpetas origen (puedes quitar o añadir más)
$carpetasImportantes = @(
    "$env:USERPROFILE\Desktop",
    "$env:USERPROFILE\Documents",
    "$env:USERPROFILE\Pictures"
    # "$env:USERPROFILE\Downloads"  # ← Descomenta si quieres incluir Descargas
)

# Carpeta de destino en disco E:\
$destinoBase = "E:\Backup_Usuario"

# Crear carpeta base si no existe
if (-not (Test-Path $destinoBase)) {
    New-Item -ItemType Directory -Path $destinoBase | Out-Null
}

# Loop para copiar cada carpeta
foreach ($origen in $carpetasImportantes) {
    $nombreCarpeta = Split-Path $origen -Leaf
    $destino = Join-Path $destinoBase $nombreCarpeta

    # Crear carpeta destino si no existe
    if (-not (Test-Path $destino)) {
        New-Item -ItemType Directory -Path $destino | Out-Null
    }

    # Copiar usando Robocopy
    Robocopy $origen $destino /MIR /R:2 /W:5 /NFL /NDL /NP /LOG+:"$env:USERPROFILE\Documents\log_backup.txt"

    Write-Output "Copiada: $nombreCarpeta a $destino"
}

Write-Output "Copia de seguridad finalizada."

