<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AttachRolesAndPermissions extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'vss:create-permission';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates roles and permissions and after attaches them to users.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$isAdminOption = $this->option('a');
		$isManagerOption = $this->option('m');
		$userId = $this->argument("id");

		// vss:create-permission -a
		// создаём админские права
		if ($isAdminOption && !isset($userId)) {
			$owner = new Role();
		    $owner->name = 'Owner';
		    $owner->save();
			
		    $root = new Permission();
		    $root->name = 'has_root';
		    $root->display_name = 'Has GODLIKE permissions ;)';
		    $root->save();
	    	
	    	$owner->attachPermission($root);
	    	$this->info('Admin privileges has been successfully created!');
		}
		
		// vss:create-permission -m
		// создаём права для менеджеров
		if ($isManagerOption && !isset($userId)) {
		    $manager = new Role();
		    $manager->name = 'Manager';
		    $manager->save();
		 
		    $toursDataManage = new Permission();
		    $toursDataManage->name = 'can_tours_data_manage';
		    $toursDataManage->display_name = 'Have access to managing tours data';
		    $toursDataManage->save();
		 
		    $manager->attachPermission($toursDataManage);
		    $this->info('Manager privileges has been successfully created!');
		}

		// vss:create-permission <id> -a
		// вешаем пользователю по id права админа
		if ($isAdminOption && isset($userId)) {
			$adminUser = User::find($userId);
			$owner = Role::where(array("Name" => "Owner"))->first();
			$adminUser->attachRole($owner);
			$this->info('Admin role has been successfully attached!');
		}

		// vss:create-permission <id> -m
		// вешаем пользователю по id права менеджера
		if ($isManagerOption && isset($userId)) {
			$managerUser = User::find($userId);
			$manager = Role::where(array("Name" => "Manager"))->first();
			$managerUser->attachRole($manager);
			$this->info('Manager role has been successfully attached!');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('id', InputArgument::OPTIONAL, 'User Id'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('a', null, InputOption::VALUE_NONE, 'Creates permissions for admins', null),
			array('m', null, InputOption::VALUE_NONE, 'Creates permissions for managers', null),
		);
	}

}
