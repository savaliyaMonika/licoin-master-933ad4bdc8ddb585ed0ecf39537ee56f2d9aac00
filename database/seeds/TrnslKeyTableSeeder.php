<?php

use Illuminate\Database\Seeder;
use App\Models\TrnslKey;
use App\Models\TransIbmUrlKey;

class TrnslKeyTableSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $dataJSON =  '[
        {"name": "chintan facebook", "key": "trnsl.1.1.20181120T212751Z.b0cf8e0230f1fa0e.c8a125056f33c088ddbfec82760191d61bdfe9c6","api_key_type":"yandex"},
        {"name": "ishita", "key": "trnsl.1.1.20190215T093442Z.760e01be9f90ab69.71e08eb34abc659f5d1c38548337edce21abd335","api_key_type":"yandex"},
        {"name": "jaydip sardhara", "key": "trnsl.1.1.20190215T093611Z.820cdaf5741e13f3.03b43ab21eaf7f8bb965c708088fed908d697a54","api_key_type":"yandex"},
        {"name": "jaydip boricha", "key": "trnsl.1.1.20190215T093532Z.a2fd2a1a9c9f10c4.7258ae567e745c52ad4cac815d0e8c3a36f7f325","api_key_type":"yandex"},
        {"name": "amita", "key": "trnsl.1.1.20190215T093435Z.7f7397cb9b65a693.e5e23002195a32595e3eb805ef4e9d7de69d19ba","api_key_type":"yandex"},
        {"name": "hemakshi", "key": "trnsl.1.1.20190215T093419Z.a6b5b260b9e08cb5.f820e8bd44eed12ddc4b94d387155495cc5654e6","api_key_type":"yandex"},
        {"name": "digvijay", "key": "trnsl.1.1.20190215T093232Z.4df59967da16774b.3b0af240c2367f99420f5353224b0a20be71f65b","api_key_type":"yandex"},
        {"name": "gaurav", "key": "trnsl.1.1.20190509T050509Z.0414256387d563a2.ce943f83510f192ff9a3d8e747e5b1a9ef5964f7","api_key_type":"yandex"},
        {"name": "hiren", "key": "trnsl.1.1.20190215T093356Z.c76dd00ac56cdb5b.96c7a4feb2065f30b7c5df31742909b44b3f7221","api_key_type":"yandex"},
        {"name": "bhavik", "key": "trnsl.1.1.20190513T114421Z.1d58206159a356d3.22972f3199d89db8112743311a49a4a802ddae8e","api_key_type":"yandex"},
        {"name": "pooja rajkotiya", "key": "trnsl.1.1.20190513T114421Z.1d58206159a356d3.22972f3199d89db8112743311a49a4a802ddae8e","api_key_type":"yandex"},
        {"name": "dipak", "key": "trnsl.1.1.20190513T114602Z.0b1e49900c407a25.5b515ad4d2c2b6d0ad33bc5c0409190d1ce018b0","api_key_type":"yandex"},
        {"name": "nirav kakadiya", "key": "trnsl.1.1.20190513T115349Z.911cb35457028ce9.5837844ab7a524c87bdde5ad18361b2d7ec81672","api_key_type":"yandex"},

        {"name": "Jemish Harsora", "key": "0g8k17zRT9q9pyv-Q1lSbRL4ZbpmafbOPoV3eOpYzxkZ","api_key_type":"ibm","url_key":"2e35c8ce-bcfe-4f74-8f9a-1e433feb6ebb"},
        {"name": "Samip logistic", "key": "GwWVkotTHe4gZTudTCKrDOdjniqK6p4p-s-sJlpTUEl2","api_key_type":"ibm","url_key":"e7e54e8b-e78a-49e2-b72c-eb66a03eec79"},
        {"name": "Dhaval logistic", "key": "XkfyFZ0xZlQUHjFoDMFAhUf7ZER8pdUxX9sV-yRv1v6N","api_key_type":"ibm","url_key":"15875b56-0a2c-4212-894f-4d6f2932f1e9"},
        {"name": "Sanket logistic", "key": "8vebrHt60J_pgiSheEU_wmmSZZpIheL9uPV_MwCeaW5f","api_key_type":"ibm","url_key":"bc00bd27-7fdf-461c-8245-e2b17ae202c9"},
        {"name": "Ajay logistic", "key": "YQ61PI_cAWeQ5SWnLAiccXZiVv4Ir5BjSagpH9DVAFWC","api_key_type":"ibm","url_key":"37d25476-238a-43a5-a1f4-f6d4c08dd469"},
        {"name": "vasita logistic", "key": "-oYnXdhh3Se7Zit4o6vrp2GyfUA4khfvg1dhiiOV2a7P","api_key_type":"ibm","url_key":"5a124581-f4ae-45bb-b6a0-ca50c038f4d9"},
        {"name": "raxit logistic", "key": "GIqPU8UBN20cW-iPSvuK6kfGLItD5k2by3GlmJUpKme1","api_key_type":"ibm","url_key":"d623e22c-3367-49f2-af92-6b85c5d272d9"},
        {"name": "Nirav logistic", "key": "ZnSGY5PFBtis1fqskTqYr68FCAKfq1iFUygGdt2iMKGZ","api_key_type":"ibm","url_key":"05894158-3782-43a6-a1dc-f952b52b078c"},
        {"name": "Shubham Vala", "key": "KJ3oR7jWCK0vSC1W4NW_e2nuFC_51HKmaDOg84liVvOL","api_key_type":"ibm","url_key":"3078bdb8-1820-453a-b260-914a9236e1ea"}
      ]';

    $dataArray = json_decode($dataJSON, true);

    foreach ($dataArray as $data) {
        $trnslKey = TrnslKey::create([
                'name' => $data['name'],
                'key' => $data['key'],
                'api_key_type' => $data['api_key_type']
        ]);

        if($data['api_key_type'] == 'ibm'){
            TransIbmUrlKey::create([
                'trans_key_id' => $trnslKey->id,
                'url_key' => $data['url_key'],
            ]);
        }
    }
  }
}
