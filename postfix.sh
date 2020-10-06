sudo vi /etc/postfix/sasl_passwd
SMTP.SERVER:PORT USERNAME:PASSWORD

sudo postmap /etc/postfix/sasl_passwd

sudo cp /etc/postfix/main.cf /etc/postfix/main.cf.orig

sudo vi /etc/postfix/main.cf
# Basic
mydomain = DOMAIN.NAME
myorigin = $mydomain
mail_owner = _postfix
setgid_group = _postdrop
# SMTP
relayhost=SMTP.SERVER:PORT
# Enable SASL (Simple Authentication and Security Layer)
smtp_sasl_auth_enable=yes
smtp_sasl_password_maps=hash:/etc/postfix/sasl_passwd
smtp_sasl_security_options=noanonymous
smtp_sasl_mechanism_filter=plain
# Enable TLS (Transport Layer Security)
smtp_use_tls=yes
smtp_tls_security_level=encrypt
tls_random_source=dev:/dev/urandom

sudo postfix start
sudo postfix reload

date | mail -s POSTFIX TEST@EXAMPLE.COM

mailq
sudo postsuper -d ALL
