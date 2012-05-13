class Php < Thor

  desc "start server", "start the lampp php server"
  def start
    system('sudo /opt/lampp/lampp start')
  end

  desc "stop server", "stop the lampp php server"
  def stop
    system('sudo /opt/lampp/lampp stop')
  end

  desc "switch directory", "change the current php directory to your current one"
  def switch
    web_dir = Dir.pwd

    Dir.chdir('/opt/lampp')
    system("sudo rm -f htdocs")
    system("sudo ln -s #{web_dir} htdocs")

    puts "Switched web directory to #{web_dir}."
  end

end

