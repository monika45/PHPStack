<?php
/**
* 重载示例
* PHP的重载与其他语言的重载不一样，
* 其他语言的重载是定义相同的方法名，不同的参数，即signature不同，
* 而PHP里面的重载是指利用魔术方法动态生成属性或方法。
* 重载属性的魔术方法：__get __set __isset __unset  ，必须定义为public.这些魔术方法只能在对象中调用，不能在静态方法中调用。
* 重载方法的魔术方法：__call __callStatic
*/
class A 
{

	private $a4 = 20;



	/**
	* 当给不可访问的属性赋值时，进入__set()魔术方法。
	*/
	public function __set($name, $value)
	{
		echo "变量：{$name}, 设置为：{$value}<br>";
		$this->$name = $value;//动态生成属性，
	}

	/**
	* 当调用当前作用域内未定义的属性或方法时，
	* 进入__get魔术方法。
	*/
	public function __get($name)
	{
		// echo "$name is not defined.So,go into __get()";
		// 可以在此根据不同的变量名，做不同的操作和返回值
		switch ($name) {
			case 'a1':
				return 'a1_001';
			case 'a2':
				return 'a2_001';
			default:
				return 'undefined';
		}
	}


	/**
	* 当对未定义的属性进行empty、isset操作时,进入__isset方法
	*/
	public function __isset($name)
	{
		echo "{$name},go into __isset.";
		var_dump(empty($this->$name));
	}

	/**
	* 当对不可访问的属性进行unset时，调用__unset()方法
	*/
	public function __unset($name)
	{
		echo "{$name},__unset.";
	}

	/**
	* 在对象上调用未定义的方法时，进入__call
	*/
	public function __call($name, $arguments)
	{
		echo "调用了未定义的方法{$name},参数：" . json_encode($arguments);
	}

	/*
	* 调用未定义的静态方法时，进入__callStatic
	*/
	public static function __callStatic($name, $arguments)
	{
		echo "调用了未定义的静态方法{$name},参数：" . json_encode($arguments);
	}

	public function a1()
	{
		echo $this->a1;//调未定义的属性
	}

	public function a3()
	{
		$this->a3 = 10;//对未定义的属性赋值，会走__set()方法
		echo $this->a3;//如果a3未定义，会走__get()方法。

		$this->a4 = 30;//a4在前面已定义，这里是修改a4的值，不会走__set()方法。
		var_dump($this->a4);//a4已定义，不会走__get()方法
	}

	public static function a5()
	{
		echo self::$a5;//未定义a5,会报错，因为是在静态方法中，所以不调用魔术方法__get()
	}

	public function a6()
	{
		var_dump(isset($this->a6));//a6未定义，进入__isset
		var_dump(empty($this->a6));
	}


	public function a7()
	{
		unset($this->a7);//进入__unset()
	}

}


$ia = new A();
// $ia->a1();
// $ia->a3();
// $ia->a5();
// $ia->a6();
// $ia->a7();
// $ia->a8($a=10, $b=20, $c=30);//调用未定义的方法
$ia::a9(20, 20);//调用未定义的静态方法