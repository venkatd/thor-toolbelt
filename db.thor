class Db < Thor

  desc "dump database", "Dumps the database for the current project to a file"

  def dump(database, file = 'db.sql')
    puts "dumping database #{database}..."

    mysqldump = '/opt/lampp/bin/mysqldump'
    username, password = 'root', 123
    dest = File.join(Dir.pwd, file)

    command = "sudo #{mysqldump} --opt --user=#{username} --password=#{password} #{database} > #{file}"

    system(command)
    
    puts "dumped database to #{dest}"
  end

end