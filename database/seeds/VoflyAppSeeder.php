<?php

use App\VoflyApp;
use Illuminate\Database\Seeder;

class VoflyAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app = VoflyApp::find(1);
        if (!$app) {
            VoflyApp::create([
                "politica" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum, quasi inventore, ipsa magnam pariatur unde velit impedit voluptatibus blanditiis repellendus dignissimos nihil omnis, quisquam repudiandae accusantium! Officiis quos ullam qui. Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe incidunt eveniet reprehenderit numquam et cum. Voluptatem quos consequatur sint velit beatae accusamus eveniet minima in, eaque voluptatibus voluptate veritatis itaque non aliquid, suscipit provident aliquam explicabo.\n\nQuos ullam recusandae voluptates, quo voluptatibus labore maiores veniam iusto necessitatibus, autem dolor est sunt soluta hic qui deserunt. Placeat vitae adipisci voluptatem repudiandae velit quaerat natus ipsa ipsum ad doloribus odit deleniti veritatis, eius corrupti ipsam cupiditate aut, sint qui dolor, ut nesciunt! Delectus saepe reprehenderit fugiat voluptatem quos temporibus ut iusto iste tenetur aperiam ipsam, quo quia distinctio debitis, consectetur magnam animi.\n\nLorem ipsum dolor sit amet consectetur adipisicing elit. Saepe tempora dolorem earum omnis officia eius repellat iusto doloribus iste, quam ea excepturi deleniti ab, exercitationem itaque ut corrupti atque quasi fugit deserunt voluptas velit. Saepe similique magnam veniam, debitis eligendi exercitationem eos omnis atque. Excepturi voluptates odit fugiat commodi nihil atque rem eligendi, quaerat debitis? Accusamus ex rem sint doloremque quam quo excepturi, ad incidunt maiores amet, similique reiciendis voluptatum voluptas eum optio doloribus deleniti modi explicabo vel veniam et quibusdam aspernatur odio pariatur!\n\nMagni, voluptates in illum excepturi, id vero asperiores nulla repellendus est numquam corrupti quaerat dolorum, cupiditate deleniti atque veritatis exercitationem modi libero! Necessitatibus rem sapiente doloribus eos eius vitae commodi deserunt, perferendis neque esse dolor dolorum? Deleniti facere esse odio, nulla aliquid ex error, labore eum iure, expedita corporis quibusdam? Alias debitis velit architecto magnam esse."
            ]);
        }
    }
}
