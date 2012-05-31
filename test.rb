require 'net/ftp'

require 'rubygems'
require 'debugger'


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

  def download(remote_filepath, local_filepath)
    @ftp.getbinaryfile(remote_filepath, local_filepath)
  end

  def upload(remote_filepath, local_filepath)
    @ftp.putbinaryfile(remote_filepath, local_filepath)
  end

  def delete(filepath)_
    @ftp.delete(filepath)
  end

  def create_dir(dirname)
    @ftp.mkdir(dirname)
    #todo make recursive to create the whole path
  end

  def create_path(path)
    each_directory_in_path(path) do |cur_path|
      create_dir(cur_path) unless dir_exists?(cur_path)
    end
  end

  def each_directory_in_path(path)
    parts = path.split('/')
    parts.each_index do |index|
      cur_path = parts[0..index].join('/')
      yield cur_path
    end
  end

  def destroy_dir(dirname)
    @ftp.rmdir(dirname)
  end

  def dir_exists?(directory)
    prev_dir = @ftp.getdir

    begin
      @ftp.chdir(directory)
      success = true
    rescue Net::FTPPermError => e
      success = false
    end

    @ftp.chdir(prev_dir)

    return success
  end


  def close
    @ftp.close
  end

end

class GitChange

  attr_accessor :operation
  attr_accessor :filepath

  def self.from_line(line)
    change_names = {'M' => :modified, 'C' => :copied, 'R' => :renamed, 'A' => :added, 'D' => :deleted, 'U' => :unmerged}

    operation = change_names[ line[0] ]
    filepath = line[1..-1].strip

    GitChange.new(operation, filepath)
  end

  def initialize(operation, filepath)
    self.operation = operation
    self.filepath = filepath
  end

  def modified?
    operation == :modified
  end
  alias_method :changed?, :modified?

  def copied?
    operation == :copied
  end

  def renamed?
    operation == :renamed
  end

  def added?
    operation == :added
  end
  alias_method :created?, :added?

  def deleted?
    operation == :deleted
  end

  def unmerged?
    operation == :unmerged
  end

  def to_s
    "#{operation}: #{filepath}"
  end

end

class GitChangeset < Array

  def self.of_cwd(a, b)
    changes = GitChangeset.new

    command = "git diff --name-status #{a} #{b}"
    output = IO.popen(command)
    output.readlines.each do |line|
      changes << GitChange.from_line(line)
    end

    return changes
  end

  def with_operation(op)
    op = op.to_sym
    keep_if { |change| change.operation == op }
  end

  def modified
    with_operation(:modified)
  end

  def added
    with_operation(:added)
  end

  def deleted
    with_operation(:deleted)
  end

end


def git_head_hash
  `git rev-parse HEAD`
end

storage = FtpStorage.new('50.63.212.1', 'venkatdinavahi', 'Dinavahi1979')


#storage.create_dir('yee') unless storage.exists?('yee')
#storage.create_dir('yee/haw') unless storage.exists?('yee/haw')
#storage.create_dir('yee/oo') unless storage.exists?('yee/oo')

storage.create_path('a/b/c/d')

#storage.upload('php.thor', 'php.thor')
#storage.download('2012/rotem/contact.php', 'contact.php')
storage.close

#changes = GitChangeset.of_cwd('HEAD~1', 'HEAD~3')
#
#changes.added.each do |change|
#  puts change
#end



