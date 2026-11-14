#!/usr/bin/env python3
"""
Automated FTP Deployment for Simoune Nicole's IT0049 TSA3 (summative3) to InfinityFree.
Reads credentials from environment variables or gitignored env.production, builds the lean zip,
and uploads summative3-infinityfree.zip + extract.php into /htdocs on ftpupload.net.
"""

import ftplib
import os
import subprocess
import sys
from pathlib import Path

BASE_DIR = Path(__file__).resolve().parent.parent
ZIP_PATH = BASE_DIR / "summative3-infinityfree.zip"
EXTRACT_PHP = BASE_DIR / "extract.php"
ENV_PROD = BASE_DIR / "env.production"


def parse_dotenv(path: Path) -> dict[str, str]:
    values: dict[str, str] = {}
    if not path.exists():
        return values
    for raw_line in path.read_text(encoding="utf-8").splitlines():
        line = raw_line.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, val = line.split("=", 1)
        values[key.strip()] = val.strip().strip('"').strip("'")
    return values


def load_credentials() -> dict[str, str]:
    dotenv = parse_dotenv(ENV_PROD)
    ftp_user = os.getenv("FTP_USER") or dotenv.get("DB_USERNAME", "")
    ftp_pass = os.getenv("FTP_PASS") or dotenv.get("DB_PASSWORD", "")
    app_url = os.getenv("APP_URL") or dotenv.get("APP_URL", "https://it0049-tsa3.infinityfreeapp.com")

    if not ftp_user or not ftp_pass or ftp_pass == "YOUR_VPANEL_PASSWORD":
        raise RuntimeError(
            "Missing FTP credentials. Set FTP_USER/FTP_PASS or populate DB_USERNAME/DB_PASSWORD in gitignored env.production."
        )

    return {
        "FTP_HOST": os.getenv("FTP_HOST", "ftpupload.net"),
        "FTP_PORT": os.getenv("FTP_PORT", "21"),
        "FTP_USER": ftp_user,
        "FTP_PASS": ftp_pass,
        "APP_URL": app_url.rstrip("/"),
    }


def build_package() -> None:
    print("==> Packaging lean production build via build-infinityfree-zip.sh ...")
    res = subprocess.run([str(BASE_DIR / "build-infinityfree-zip.sh")], cwd=str(BASE_DIR))
    if res.returncode != 0:
        raise RuntimeError("Build script failed.")


def deploy() -> None:
    creds = load_credentials()
    build_package()

    host = creds["FTP_HOST"]
    port = int(creds["FTP_PORT"])
    user = creds["FTP_USER"]
    password = creds["FTP_PASS"]

    print(f"Connecting to FTP {host}:{port} as {user} ...")
    try:
        ftp: ftplib.FTP = ftplib.FTP_TLS(timeout=45)
        ftp.connect(host, port)
        ftp.auth()
        ftp.prot_p()
        ftp.login(user, password)
        print("FTPS (TLS) connected and authenticated.")
    except Exception:
        ftp = ftplib.FTP(timeout=45)
        ftp.connect(host, port)
        ftp.login(user, password)
        print("FTP connected and authenticated.")

    try:
        ftp.cwd("htdocs")
        print("Changed remote directory to /htdocs")

        try:
            files = ftp.nlst()
            if "index2.html" in files:
                ftp.delete("index2.html")
                print("Removed default hosting placeholder 'index2.html'.")
        except Exception as exc:
            print(f"Note on index2.html removal: {exc}")

        zip_size_mb = ZIP_PATH.stat().st_size / (1024 * 1024)
        print(f"==> Uploading {ZIP_PATH.name} ({zip_size_mb:.2f} MB) via FTP ...")
        with ZIP_PATH.open("rb") as f:
            ftp.storbinary(f"STOR {ZIP_PATH.name}", f, blocksize=65536)
        print(f"Uploaded {ZIP_PATH.name} successfully.")

        print("==> Uploading extract.php ...")
        with EXTRACT_PHP.open("rb") as f:
            ftp.storbinary("STOR extract.php", f)
        print("Uploaded extract.php successfully.")

        print("\n==> Remote /htdocs listing:")
        ftp.dir()

        print("\n" + "=" * 56)
        print(" DEPLOYMENT UPLOAD COMPLETE")
        print(f" Next step: Trigger extraction at {creds['APP_URL']}/extract.php")
        print("=" * 56)
    finally:
        try:
            ftp.quit()
        except Exception:
            pass


if __name__ == "__main__":
    try:
        deploy()
    except Exception as exc:
        print(f"Deployment failed: {exc}", file=sys.stderr)
        sys.exit(1)
