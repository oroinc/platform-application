bash "set default locale to UTF-8" do
  code <<-EOH
update-locale LANG=en_US.UTF-8 LC_ALL=en_US.UTF-8
dpkg-reconfigure locales
EOH
end

bash "Add PPA for latest PHP" do
  code <<-EOH
  sudo add-apt-repository ppa:ondrej/php5
  EOH
end

# dont't prompt for host key verfication (if any)
template "/home/vagrant/.ssh/config" do
  user "vagrant"
  group "vagrant"
  mode "0600"
  source "config"
end

execute "apt-get update"

# install the software we need
%w(
curl
tmux
vim
git
mysql-server
php5
php5-cli
php5-curl
php5-intl
php5-dev
php5-xsl
php5-mysql
php5-ldap
php-pear
phpMyAdmin
python-software-properties
apache2
libapache2-mod-php5
nodejs
build-essential
openssl
libssl-dev
).each { | pkg | package pkg }

# we want the latest xdebug with trace triggering
execute "install newest xdebug via pecl" do
  not_if "pecl list | grep xdebug"
  command "pecl install xdebug"
end

# modify xdebug configuration
template "/etc/php5/conf.d/xdebug.ini" do
  mode "0644"
  source "xdebug.ini"
  notifies :restart, "service[apache2]"
end

template "/home/vagrant/.bash_aliases" do
  user "vagrant"
  mode "0644"
  source ".bash_aliases.erb"
end

template "/home/vagrant/.bash_profile" do
  user "vagrant"
  group "vagrant"
  source ".bash_profile"
end

file "/etc/apache2/sites-enabled/000-default" do
  action :delete
end

template "/etc/apache2/sites-enabled/vhost.conf" do
  user "root"
  mode "0644"
  source "vhost.conf.erb"
  notifies :reload, "service[apache2]"
end

link "/etc/apache2/conf.d/phpmyadmin.conf" do
    to "/etc/phpmyadmin/apache.conf"
end

execute "a2enmod rewrite"
execute "a2enmod php5"


service "apache2" do
  supports :restart => true, :reload => true, :status => true
  action [ :enable, :start ]
end

# phpmyadmin settings and permissions
execute "allow phpmyadmin access from host" do
  not_if "grep '#{node[:host_ip]}' /etc/apache2/conf.d/phpmyadmin.conf"
  command "sed -i 's/Allow from 127\.0\.0\.1/Allow from 127.0.0.1\\nAllow from #{node[:host_ip]}/g' /etc/apache2/conf.d/phpmyadmin.conf"
  notifies :reload, "service[apache2]"
end

execute "allow empty password for phpmyadmin" do
  not_if "grep -F \"\\$cfg['Servers'][\\$i]['AllowNoPassword'] = TRUE\" /etc/phpmyadmin/config.inc.php | grep -v //"
  command "sed -i '$i$cfg[\\x27Servers\\x27][$i][\\x27AllowNoPassword\\x27] = TRUE;' /etc/phpmyadmin/config.inc.php"
end

execute "date.timezone = UTC in php.ini?" do
 user "root"
 not_if "grep 'date.timezone = UTC' /etc/php5/cli/php.ini"
 command "echo -e '\ndate.timezone = UTC\n' >> /etc/php5/cli/php.ini"
end

bash "retrieve composer" do
  user "vagrant"
  cwd "/vagrant"
  code <<-EOH
  set -e

  # create bin folder
  mkdir -p bin

  # check if composer is installed
  if [ ! -f composer.phar ]
  then
    curl -s https://getcomposer.org/installer | php
  else
    php composer.phar selfupdate
  fi
  EOH
end

bash "run composer" do
  user "vagrant"
  cwd "/vagrant"
  code <<-EOH
  set -e
  export COMPOSER_HOME=/home/vagrant
  ./composer.phar install --prefer-dist
  EOH
end

bash "Create database" do
  user "vagrant"
  cwd "/vagrant"
  not_if "echo 'show databases' | mysql -u root | grep bap_standard"
  code <<-EOH
  set -e
  echo "create database bap_standard" | mysql -u root
  EOH
end


log "\n" +
    "#################################################\n" +
    "# Now you might want to run the following cmd   #\n" + 
    "# to install the oro platform application:      #\n" +
    "#                                               #\n" +
    "# php app/console oro:install --env prod        #\n" +
    "#                                               #\n" +
    "#################################################\n"
