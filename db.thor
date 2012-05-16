class Db < Thor

  desc "dump database", "Dumps the database for the current project to a file"

  def export(database, file = 'db.sql')
    puts "dumping database #{database}..."

    mysqldump = '/opt/lampp/bin/mysqldump'
    dest = File.join(Dir.pwd, file)

    command = "sudo #{mysqldump} --opt --user=#{username} --password=#{password} #{database} > #{dest}"

    system(command)
    
    puts "dumped database to #{dest}"
  end

  desc "import database", "Import the specified sql dump into a database"
  def import(file, database)
    src = File.join(Dir.pwd, file)

    exec("CREATE DATABASE IF NOT EXISTS #{database}")
    command = "sudo mysql --user=#{username} --password=#{password} --verbose #{database} < #{src}"
    system(command)
  end

  #mysql --host=127.0.0.1 --port=3308 --user=desirae --password=E7ixPmWI
  desc "connect to remote database", "launch the mysql terminal for the remote database"
  def remote(user, password, host='127.0.0.1', port=3308)
    start_tunnel_command = "pagoda -a allianceandalliance tunnel -c db1 &"
    command = "mysql --host=#{host} --port=#{port} --user=#{user} --password=#{password}"

    system(start_tunnel_command)

    sleep 4

    system(command)

  end

    #mysql --host=127.0.0.1 --port=3308 --user=desirae --password=E7ixPmWI
  desc "connect to remote database", "launch the mysql terminal for the remote database"
  def rimport(user, password, host='127.0.0.1', port=3308)
    start_tunnel_command = "pagoda -a allianceandalliance tunnel -c db1 &"
    import_command = "mysql --host=#{host} --port=#{port} --user=#{user} --password=#{password} --verbose allianceandalliance < db.sql"

    system(start_tunnel_command)

    sleep 4

    system(import_command)

  end

  private

  def exec(sql)
    command = "sudo mysql --user=#{username} --password=#{password} --verbose -e \"#{sql}\""
    system(command)
  end

  def username
    'root'
  end

  def password
    '123'
  end

end