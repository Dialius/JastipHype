@echo off
echo ========================================
echo   Sync Storage to Public
echo ========================================
echo.
echo Copying files from storage/app/public to public/storage...
echo.

xcopy "storage\app\public\*" "public\storage\" /E /I /Y /Q

echo.
echo ========================================
echo   Sync Complete!
echo ========================================
echo.
echo All images have been synced to public/storage
echo.
pause
