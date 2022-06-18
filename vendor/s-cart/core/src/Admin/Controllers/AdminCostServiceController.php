<?php
namespace SCart\Core\Admin\Controllers;

use SCart\Core\Admin\Controllers\RootAdminController;
use SCart\Core\Front\Models\ShopCostService;
use Validator;

class AdminCostServiceController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $data = [
            'title' => sc_language_render('admin.costservice.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . sc_language_render('admin.costservice.add_new_title'),
            'subTitle' => '',
            'icon' => 'fas fa-hand-holding-usd',
            'urlDeleteItem' => sc_route_admin('costservice.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '',
            'js' => '',
            'url_action' => sc_route_admin('admin_costservice.create'),
        ];

        $listTh = [
            'value' => sc_language_render('admin.costservice.value'),
            'name' => sc_language_render('admin.costservice.name'),
            'action' => sc_language_render('action.title'),
        ];
        $obj = new ShopCostService;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
                'name' => $row['name'],
                'value' => $row['value'],
                'action' => '
                    <a href="' . sc_route_admin('admin_costservice.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . sc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
                  <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . sc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = sc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';

       
        return view($this->templatePathAdmin.'screen.costservice')
            ->with($data);
    }


    /**
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'value' => 'min:0',
        ], [
            'name.required' => sc_language_render('validation.required', ['attribute' => sc_language_render('admin.costservice.name')]),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }

        $dataInsert = [
            'value' => (float)$data['value'],
            'name' => $data['name'],
        ];
        $dataInsert = sc_clean($dataInsert, [], true);
        $obj =ShopCostService::create($dataInsert);

        return redirect()->route('admin_costservice.index')->with('success', sc_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $cost = ShopCostService::find($id);
       

        if (!$cost) {
            return 'No data';
        }
        $data = [
            'title' => sc_language_render('admin.costservice.list'),
            'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . sc_language_render('action.edit'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => sc_route_admin('admin_costservice.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '',
            'js' => '',
            'url_action' => sc_route_admin('admin_costservice.edit', ['id' => $cost['id']]),
            'cost' => $cost,
            'id' => $id,
        ];


        $listTh = [
            'name' => sc_language_render('admin.costservice.name'),
            'value' => sc_language_render('admin.costservice.value'),
            'action' => sc_language_render('action.title'),
        ];
        $obj = new ShopCostService;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[$row['id']] = [
                'name' => $row['name'],
                'value' => $row['value'],
                'action' => '
                    <a href="' . sc_route_admin('admin_costservice.edit', ['id' => $row['id'] ? $row['id'] : 'not-found-id']) . '"><span title="' . sc_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
                <span onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . sc_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = sc_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->templatePathAdmin.'screen.costservice')
            ->with($data);
    }


    /**
     * update status
     */
    public function postEdit($id)
    {
        $cost = ShopCostService::find($id);
        $data = request()->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'value' => 'min:0',
        ], [
            'name.required' => sc_language_render('validation.required', ['attribute' => sc_language_render('admin.cost.name')]),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        //Edit

        $dataUpdate = [
            'value' => (float)$data['value'],
            'name' => $data['name'],
        ];
        $dataUpdate = sc_clean($dataUpdate, [], true);
        $cost->update($dataUpdate);

        return redirect()->back()->with('success', sc_language_render('action.edit_success'));
    }

    /*
    Delete list item
    Need mothod destroy to boot deleting in model
     */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => sc_language_render('admin.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            ShopCostService::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}

