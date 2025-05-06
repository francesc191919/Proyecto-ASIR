# 1. Eliminar archivos de más de 30 días en la carpeta Descargas
$descargas = "$env:USERPROFILE\Downloads"
$limite = (Get-Date).AddDays(-30)

Write-Output "Eliminando archivos antiguos en: $descargas"
Get-ChildItem -Path $descargas -File -Recurse | Where-Object {
    $_.LastWriteTime -lt $limite
} | ForEach-Object {
    try {
        Remove-Item $_.FullName -Force
        Write-Output "Eliminado: $($_.FullName)"
    } catch {
        Write-Output "Error eliminando: $($_.FullName) - $_"
    }
}

# 2. Vaciar la Papelera de reciclaje
Write-Output "Vaciando la Papelera de reciclaje..."

# Cargar el objeto COM para acceder a la Papelera
$shell = New-Object -ComObject Shell.Application
$papelera = $shell.Namespace(0xA)  # 0xA es la carpeta especial de la Papelera

# Borrar los elementos
$papelera.Items() | ForEach-Object {
    try {
        $_.InvokeVerb("delete")
        Write-Output "Eliminado de la Papelera: $($_.Name)"
    } catch {
        Write-Output "Error al eliminar de la Papelera: $($_.Name) - $_"
    }
}

Write-Output "Limpieza completada."
