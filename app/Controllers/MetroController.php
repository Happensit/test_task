<?php

namespace Task\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class MetroController extends \Task\Controllers\BaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {

        return $this->render('index.html.twig');

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function importAction(Request $request)
    {

        if ($request->get('metro')) {
            return new JsonResponse("Request GET");
        }

//        if ($this->getCache()->contains('all_data')) {
//            $a = $this->getCache()->fetch('all_data')."\r\n";
//        } else {

//            $this->getCache()->save('all_data', $a, 30);
//        }
        return new JsonResponse(["errors" => ["type" => "Not found"]], Response::HTTP_NOT_FOUND);

    }


    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAll()
    {
        $a = $this->getDb()->fetchAll('SELECT s.*, l.id as line_id, l.name as line_name, l.color as line_color FROM `stations` as s LEFT JOIN `lines` as l ON s.line_id = l.id ORDER BY l.id, s.order ASC');

        if (!empty($a)) {
            return new JsonResponse(['station' => $a]);
        }

        return new JsonResponse( ["errors" => ["type" => "Not found"]], Response::HTTP_NOT_FOUND );
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getStation($id)
    {

        if (is_numeric($id)) {
            $select = $this->getDb()->fetchAssoc(
              'SELECT s.*, l.id as line_id, l.name as line_name, l.color as line_color FROM `stations` as s LEFT JOIN `lines` as l ON s.line_id = l.id WHERE s.id = ?',
              array((int)$id)
            );

            if (!empty($select)) {
                return new JsonResponse(['station' => $select]);
            }
        }

        return new JsonResponse(["errors" => ["type" => "Not found"]], Response::HTTP_NOT_FOUND);
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newStation(Request $request)
    {

        if($request->getMethod() === 'POST') {
            $name = $request->get('name');
            $order = $request->get('order');
            $line_id = $request->get('line_id');
            $lat = $request->get('lat');
            $lng = $request->get('lng');

            $save = $this->getDb()->insert('`stations`', array(
                '`name`' => $name,
                '`order`' => $order,
                '`line_id`' => (int)$line_id,
                '`lat`' => $lat,
                '`lng`' => $lng,
              )
            );

            if($save == true){
                return new JsonResponse($save, Response::HTTP_CREATED);
            }

        }

        return new JsonResponse(["errors" => ["type" => "Method Not Allowed"]], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateStation(Request $request, $id)
    {

        if($request->getMethod() === 'PATCH') {
            $name = $request->get('name');
            $order = $request->get('order');
            $line_id = $request->get('line_id');
            $lat = $request->get('lat');
            $lng = $request->get('lng');

            $save = $this->getDb()->update('`stations`', array(
                '`name`' => $name,
                '`order`' => $order,
                '`line_id`' => (int)$line_id,
                '`lat`' => $lat,
                '`lng`' => $lng,
              ), array('id' => (int)$id)
            );

            if($save == true){
                return new JsonResponse($save, Response::HTTP_NO_CONTENT);
            }

        }

        return new JsonResponse(["errors" => ["type" => "Method Not Allowed"]], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteStation(Request $request, $id)
    {

        if($request->getMethod() === 'DELETE') {

            $save = $this->getDb()->delete('`stations`', array('id' => (int)$id));

            if($save == true){
                return new JsonResponse($save, Response::HTTP_NO_CONTENT);
            }

        }

        return new JsonResponse(["errors" => ["type" => "Method Not Allowed"]], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getLineStation(Request $request, $id)
    {

        if (is_numeric($id)) {

            $sql = "SELECT s.*, l.id as line_id, l.name as line_name, l.color as line_color FROM `stations` as s
                        LEFT JOIN `lines` as l ON s.line_id = l.id
                        WHERE s.line_id = ?";

            $select = $this->getDb()->fetchAll($sql, array((int)$id));

            if (!empty($select)) {
                return new JsonResponse(['station' => $select]);
            }
        }


        return new JsonResponse(["errors" => ["type" => "Not found"]], Response::HTTP_NOT_FOUND);
    }

}