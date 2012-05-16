require 'rubygems'
require 'mechanize'
require 'json'
require 'debugger'
require 'logger'

class PHPMyAdmin

  def initialize(login_url, server, username, password)
    @login_url = login_url
    @server = server
    @username = username
    @password = password

    @agent = Mechanize.new
    @agent.user_agent_alias = 'Mac Safari'
    @agent.follow_meta_refresh = true
    @agent.log = Logger.new(STDOUT)

    @agent.read_timeout = 30
  end

  def loginpf
    get_phpfog_app_info('berenholtzdan@gmail.com', 'WWO12345', '41074')
  end

  def login
    login_page = @agent.get(@login_url)

    login_form = login_page.form_with(class: 'login')

    login_form.pma_servername = @server
    login_form.pma_username = @username
    login_form.pma_password = @password

    #debugger

    page = @agent.submit(login_form, login_form.buttons.first, {
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Encoding' => 'gzip,deflate,sdch',
        'Accept-Language' => 'en-US,en;q=0.8',
        'Cache-Control' => 'max-age=0',
        'Connection' => 'keep-alive',
        'Content-Type' => 'application/x-www-form-urlencoded',
        #'Host' => 'phpfog.com',
        #'Origin' => 'https://phpfog.com',
        #'Referer' => 'https://phpfog.com/login',
        #'User-Agent'=> 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.19 (KHTML, like Gecko) Ubuntu/12.04 Chromium/18.0.1025.151 Chrome/18.0.1025.151 Safari/535.19'
    })

    pp page
  end

  def get_phpfog_app_info(username, password, app_id)
    login_url = 'https://www.phpfog.com/login'

    login_page = @agent.get(login_url)
    login_form = login_page.forms.first

    login_form['user_session[login]'] = username
    login_form['user_session[password]'] = password

    page = @agent.submit(login_form, login_form.buttons.first, {
            'Accept' => 'text/html',
            #'Accept-Encoding' => 'gzip,deflate,sdch',
            #'Accept-Language' => 'en-US,en;q=0.8',
            #'Connection' => 'keep-alive',
            #'Content-Type' => 'application/x-www-form-urlencoded',
            #'Host' => 'phpfog.com',
            #'Origin' => 'https://phpfog.com',
            #'Referer' => 'https://phpfog.com/login',
            #'User-Agent'=> 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.19 (KHTML, like Gecko) Ubuntu/12.04 Chromium/18.0.1025.151 Chrome/18.0.1025.151 Safari/535.19'
        })
    #auth_token = JSON.parse(page.body)['api-auth-token']

    app_page = @agent.get("https://www.phpfog.com/apps/#{app_id}")

    #return JSON.parse(app_page.body)

    pp app_page
  end

end

admin = PHPMyAdmin.new('https://myadmin.phpfogapp.com/', 'mysql-shared-02.phpfog.com', 'berenholtz-41074', 'yU26q36Q81mS')
admin.login


puts "done!"





