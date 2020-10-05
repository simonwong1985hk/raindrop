# Install Elasticsearch with RPM

rpm --import https://artifacts.elastic.co/GPG-KEY-elasticsearch

vi /etc/yum.repos.d/elasticsearch.repo
[elasticsearch]
name=Elasticsearch repository for 7.x packages
baseurl=https://artifacts.elastic.co/packages/7.x/yum
gpgcheck=1
gpgkey=https://artifacts.elastic.co/GPG-KEY-elasticsearch
enabled=0
autorefresh=1
type=rpm-md

yum install --enablerepo=elasticsearch elasticsearch

vi /etc/sysconfig/elasticsearch
ES_JAVA_OPTS="-Djna.tmpdir=/var/lib/elasticsearch/tmp"

mkdir -p /var/lib/elasticsearch/tmp

chown -R elasticsearch:elasticsearch /var/lib/elasticsearch/tmp/

systemctl daemon-reload
systemctl enable elasticsearch.service

systemctl start elasticsearch.service

curl -X GET "localhost:9200/?pretty"
