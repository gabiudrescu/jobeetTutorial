set :application, "jobeetTutorial"
set :domain,      "jobeet.gabiudrescu.com"
set :deploy_to,   "/var/www/vhosts/gabiudrescu.com/#{domain}"
set :app_path,    "app"

set :repository,  "https://github.com/gabiudrescu/jobeetTutorial.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set   :use_sudo,      false
set   :keep_releases, 3

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

after "deploy", "deploy:cleanup"

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]


set :use_composer, true
set :update_vendors, true

set :user, 'gabiudr'

set :ssh_options, { forward_agent: true, user: fetch(:user), keys: %w(~/.ssh/id_rsa.pub), port: '22123' }