class Drupal < Thor

  desc "create drupal project", "Create and install a fresh Drupal project"
  def create(name)
    folder_name = 'drupal-7.14'
    archive_name = "#{folder_name}.tar.gz"
    drupal_download_link = "http://ftp.drupal.org/files/projects/#{archive_name}"

    command = "wget #{drupal_download_link}"
    system(command)

    system("tar -xvzf #{archive_name}")
    system("mv #{folder_name} #{name}")
    system("rm #{archive_name}")
  end

end