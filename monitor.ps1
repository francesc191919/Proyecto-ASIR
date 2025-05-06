# Ruta del archivo de log
$logPath = "$env:USERPROFILE\Documents\monitor_sistema.csv"

# Si el archivo no existe, crea encabezado
if (-not (Test-Path $logPath)) {
    "Fecha,Hora,CPU (%),Memoria Disponible (MB)" | Out-File -FilePath $logPath -Encoding UTF8
}

# Obtener métricas
$cpu = (Get-CimInstance Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average
$mem = (Get-CimInstance Win32_OperatingSystem).FreePhysicalMemory / 1024  # En MB

# Fecha y hora
$fecha = Get-Date -Format "yyyy-MM-dd"
$hora = Get-Date -Format "HH:mm:ss"

# Agregar línea al log
"$fecha,$hora,{0:N2},{1:N0}" -f $cpu, $mem | Out-File -Append -FilePath $logPath -Encoding UTF8

# Mostrar en pantalla
Write-Output "[$hora] CPU: {0:N2}% - Memoria disponible: {1:N0} MB" -f $cpu, $mem
