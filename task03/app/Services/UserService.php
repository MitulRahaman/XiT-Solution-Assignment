<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function storeUser($data)
    {
        return $this->userRepository
            ->setName($data->name)
            ->setEmail($data->email)
            ->setPassword($data->password)
            ->storeUser();
    }

    public function accept($id)
    {
        return $this->userRepository->setId($id)->setUpdatedAt(date('Y-m-d H:i:s'))->accept();
    }

    public function decline($id)
    {
        return $this->userRepository->setId($id)->setUpdatedAt(date('Y-m-d H:i:s'))->decline();
    }

    public function fetchData()
    {
        $result = $this->userRepository->allPendingUsers();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $email = $row->email;
                $action_btn = "<div class=\"col-sm-12 col-xl-12\">
                                    <div class=\"row p-2\">
                                        <div class=\"col-sm-6 col-xl-6\">
                                                <button type=\"button\" class=\"btn btn-success\" onclick='show_accept_modal(\"$id\")'>Accept</button>
                                        </div>
                                        <div class=\"col-sm-6 col-xl-6\">
                                            <button type=\"button\" class=\"btn btn-danger\" onclick='show_decline_modal(\"$id\")'>Decline</button>
                                        </div>
                                    </div>
                                </div>";

                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $name);
                array_push($temp, $email);
                array_push($temp, $action_btn);
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                    "sEcho": 1,
                    "iTotalRecords": "0",
                    "iTotalDisplayRecords": "0",
                    "aaData": []
                }';
        }
    }


}
