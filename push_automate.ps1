# Pindah ke folder project
Set-Location "C:\Users\reyha\BACKEND-HACKATHON"

# Hapus file index.lock kalau ada
$lockFile = ".git\index.lock"
if (Test-Path $lockFile) {
    Remove-Item $lockFile -Force
    Write-Host "index.lock deleted"
}

# Tambah, commit, dan push
git add .
git commit -m "automate push"
git push origin master  #  Ganti ke 'master' kalau itu nama branch kamu
