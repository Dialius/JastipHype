# 🔧 GitHub Actions Workflow Fix Notes

## ❌ Masalah yang Ditemukan

### Error: Invalid workflow file (Line 166)

```
Invalid workflow file: .github/workflows/deploy-hostinger.yml#L166
You have an error in your yaml syntax on line 166
```

### Root Cause

Menggunakan **heredoc** dengan PHP code di dalam YAML `script:` block menyebabkan syntax error karena:

1. **Karakter khusus di YAML**: `<?php` mengandung `<` yang merupakan karakter khusus di YAML
2. **Heredoc delimiter**: Berbagai delimiter (`EOF`, `PHPEOF`, `ENDOFPHP`) tetap menyebabkan parsing error
3. **Indentasi kompleks**: PHP code dengan indentasi di dalam heredoc sulit di-parse oleh YAML

### Contoh yang GAGAL:

```yaml
script: |
  cat > public_html/index.php << 'EOF'
  <?php
  // PHP code here...
  EOF
```

**Error**: YAML parser tidak bisa handle `<?php` dengan benar

---

## ✅ Solusi yang Diterapkan

### Menggunakan Template File

Alih-alih menulis PHP code dengan heredoc di dalam YAML, gunakan **template file** yang sudah ada di repository:

1. **Buat template file**: `public_html_index.php.template`
   ```php
   <?php
   
   use Illuminate\Contracts\Http\Kernel;
   use Illuminate\Http\Request;
   
   define('LARAVEL_START', microtime(true));
   
   if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
       require $maintenance;
   }
   
   require __DIR__.'/../vendor/autoload.php';
   
   $app = require_once __DIR__.'/../bootstrap/app.php';
   
   $kernel = $app->make(Kernel::class);
   
   $response = $kernel->handle(
       $request = Request::capture()
   )->send();
   
   $kernel->terminate($request, $response);
   ```

2. **Update workflow** untuk copy template:
   ```yaml
   script: |
     # Sync files
     rsync -av --delete \
       --exclude='build' \
       --exclude='storage' \
       public/ public_html/
     
     # Copy template file (JANGAN DIUBAH!)
     cp public_html_index.php.template public_html/index.php
   ```

### Keuntungan Pendekatan Ini:

1. ✅ **Tidak ada YAML syntax error** - Template file adalah file PHP biasa
2. ✅ **Lebih mudah di-maintain** - Edit template file langsung, bukan di dalam YAML
3. ✅ **Lebih readable** - PHP code tidak tercampur dengan YAML
4. ✅ **Lebih aman** - Tidak perlu escape karakter khusus
5. ✅ **Reusable** - Template bisa digunakan untuk setup manual juga

---

## 🚫 Pendekatan yang TIDAK Berhasil

### 1. Heredoc dengan berbagai delimiter

```bash
# GAGAL - YAML syntax error
cat > file.php << 'EOF'
<?php
EOF

# GAGAL - YAML syntax error
cat > file.php << 'PHPEOF'
<?php
PHPEOF

# GAGAL - YAML syntax error
cat > file.php <<'ENDOFPHP'
<?php
ENDOFPHP
```

### 2. Printf dengan escape

```bash
# GAGAL - Terlalu kompleks dan error prone
printf '%s\n' \
  '<?php' \
  "require __DIR__.'/../vendor/autoload.php';" \
  > file.php
```

### 3. Echo multi-line

```bash
# GAGAL - Sulit maintain dan banyak escape
echo '<?php' > file.php
echo '' >> file.php
echo "require __DIR__.'/../vendor/autoload.php';" >> file.php
```

---

## 📝 Best Practices untuk GitHub Actions

### 1. Hindari Heredoc dengan Code Kompleks

Jika perlu menulis file dengan code kompleks (PHP, JavaScript, dll):
- ✅ Gunakan template file di repository
- ❌ Jangan gunakan heredoc di YAML

### 2. Gunakan Template File untuk:

- PHP files dengan syntax kompleks
- JavaScript/TypeScript files
- Configuration files dengan special characters
- Any file yang mengandung `<`, `>`, `{`, `}`, `$`, dll

### 3. Heredoc Boleh Digunakan untuk:

- Simple text files
- Shell scripts sederhana
- Configuration files sederhana (tanpa special chars)

### 4. Contoh yang AMAN:

```yaml
# ✅ AMAN - Simple text
script: |
  cat > config.txt << 'EOF'
  APP_NAME=MyApp
  APP_ENV=production
  EOF

# ✅ AMAN - Simple shell script
script: |
  cat > script.sh << 'EOF'
  #!/bin/bash
  echo "Hello World"
  EOF

# ❌ TIDAK AMAN - PHP code
script: |
  cat > index.php << 'EOF'
  <?php
  // Complex code...
  EOF
```

---

## 🔍 Debugging YAML Syntax Errors

### Tools untuk Validate YAML:

1. **Online YAML Validator**:
   - https://www.yamllint.com/
   - https://codebeautify.org/yaml-validator

2. **Command Line**:
   ```bash
   # Python
   python -c "import yaml; yaml.safe_load(open('file.yml'))"
   
   # Ruby
   ruby -ryaml -e "YAML.load_file('file.yml')"
   
   # yamllint
   yamllint file.yml
   ```

3. **GitHub Actions Syntax Check**:
   - Push ke branch test dulu
   - Lihat error message di Actions tab
   - Fix dan push lagi

### Common YAML Errors:

1. **Indentation** - Harus konsisten (2 atau 4 spaces)
2. **Special characters** - `<`, `>`, `{`, `}`, `$`, `@`, dll
3. **Quotes** - Single vs double quotes
4. **Multiline strings** - `|` vs `>`
5. **Heredoc in script** - Sering menyebabkan masalah

---

## 📚 References

### Dokumentasi:

- [GitHub Actions Workflow Syntax](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions)
- [YAML Specification](https://yaml.org/spec/1.2.2/)
- [Bash Heredoc](https://tldp.org/LDP/abs/html/here-docs.html)

### Related Issues:

- [Heredoc in GitHub Actions](https://github.com/orgs/community/discussions/26843)
- [YAML Special Characters](https://stackoverflow.com/questions/72385851)
- [GitHub Actions Script Errors](https://stackoverflow.com/questions/75572493)

---

## ✅ Checklist untuk Workflow Changes

Sebelum mengubah workflow file:

- [ ] Backup workflow file yang lama
- [ ] Test YAML syntax dengan validator
- [ ] Gunakan template file untuk code kompleks
- [ ] Hindari heredoc dengan special characters
- [ ] Test di branch terpisah dulu
- [ ] Cek GitHub Actions logs untuk errors
- [ ] Verify deployment berhasil

---

## 🎯 Summary

| Masalah | Solusi |
|---------|--------|
| YAML syntax error line 166 | Gunakan template file |
| Heredoc dengan PHP code | Copy dari template file |
| Special characters di YAML | Simpan di file terpisah |
| Maintenance sulit | Edit template, bukan YAML |
| Error prone | Template file lebih aman |

**Key Takeaway**: Untuk code kompleks dengan special characters, selalu gunakan template file di repository, jangan embed di YAML dengan heredoc!

---

**Last Updated**: 14 Februari 2026  
**Status**: ✅ FIXED  
**Solution**: Template file approach
