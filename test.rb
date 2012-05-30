require 'net/ftp'


class FtpStorage

  def initialize(host, username, password)
    connect(host, username, password)
  end

  def connect(host, username, password)
    @host, @username, @password = host, username, password

    @ftp = Net::FTP.new(@host)
    @ftp.passive = true
    @ftp.debug_mode = true
    @ftp.login(@username, @password)
  end

  def download(filepath)
    @ftp.getbinaryfile(filepath)
  end

  def close
    @ftp.close
  end

end

storage = FtpStorage.new('50.63.212.1', 'venkatdinavahi', 'Dinavahi1979')

storage.download('2012/rotem/contact.php')

storage.close