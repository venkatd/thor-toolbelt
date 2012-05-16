class Php < Thor

  desc "start server", "start the lampp php server"
  def start
    system('sudo /etc/init.d/apache2 start')
  end

  desc "stop server", "stop the lampp php server"
  def stop
    system('sudo /etc/init.d/apache2 stop')
  end

  desc "stop server", "stop the lampp php server"
  def restart
    system('sudo /etc/init.d/apache2 restart')
  end

  desc "switch directory", "change the current php directory to your current one"
  def switch
    web_dir = Dir.pwd

    Dir.chdir('/var')
    system("sudo rm -r -f www")
    system("sudo ln -s #{web_dir} www")

    puts "Switched web directory to #{web_dir}."
  end

end

