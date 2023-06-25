<?php 
    $data['bg_color'] = 'bg-white';
    $this->load->view('layouts/front/topbar_details', $data);
?>

<div class="h-full overflow-y-auto md:ovlow-y">
    <div class="mt-[4.15rem]">
        <div class="bg-white px-3 py-2.5">
            <!-- Breadcrumbs -->
            <ol class="inline-flex items-center space-x-1 md:space-x-3 mb-1">
                <li class="inline-flex items-center font-semibold">
                    <a href="<?= base_url() ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-400 ">
                    <i class='bx bx-home mr-2'></i>
                        Beranda
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm text-gray-500 md:ml-2 text-limit-1"><?= $title ?></span>
                    </div>
                </li>
            </ol>
            <!-- End Breadcrumbs -->
        </div>

        <div class="bg-white px-3 py-5 mt-1.5">
            <h1 class="font-inter text-xl font-bold text-gray-600 whitespace-pre">Tentang kami</h1>
            <div class="mt-3 font-inter text-gray-600">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi consequatur sapiente quia quo et incidunt amet nam, saepe quidem cupiditate voluptate, optio tempore doloribus voluptatum totam ad temporibus unde suscipit velit a quibusdam. 
                    
                Voluptates, amet quis? Veritatis quibusdam voluptas, ipsum pariatur asperiores, ratione magni velit totam ea, tempora quod. Ipsa illum non est aliquid itaque laboriosam et sint voluptate accusantium perferendis labore nobis libero consequatur ab inventore eaque, dolores tempore! Incidunt molestias, consectetur sunt minus est cumque possimus exercitationem maiores hic dolores, architecto repellendus nihil fugiat itaque aliquid ea perferendis facilis consequuntur? Iusto placeat nulla dolor maiores, quisquam blanditiis exercitationem.</p>
            </div>
        </div>
    </div>
</div>