<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//获取faker的实例，准备生成数据时调用。
        $faker = app(Faker\Generator::class);

    	//因为数据工厂是没有头像数据的，所以要在数据种子里面自己定义。
         $avatars = [
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/s5ehp11z6s.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/Lhd1SHqu86.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/LOnMrqbHJn.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/xAuDMxteQy.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/NDnzMutoxX.png?imageView2/1/w/200/h/200',
        ];

        $users = factory(User::class)->times(1)->make()->each(function($user,$index)use($faker,$avatars){

        	$user->avatar = $faker->randomElement($avatars);

        });

        $user_array = $users->makeVisible(['password','remember_token'])->toArray();

        User::insert($user_array);

        $user = User::find(1);
        $user->name = 'hola';
        $user->email = '2478969052@qq.com';
        $user->avatar = 'http://larabbs.test/uploads/images/avatars/201808/02/1_1533199550_w3Roa2gllV.jpeg';
        $user->save();

        // 初始化用户角色，将 1 号用户指派为『站长』
    }
}
