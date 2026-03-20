<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Tours\Tables\TourEnquiryTable;
use Botble\Tours\Models\TourEnquiry;

class TourEnquiryController extends BaseController
{
    public function index(TourEnquiryTable $table)
    {
        $this->pageTitle(trans('plugins/tours::tours.enquiries'));
        return $table->renderTable();
    }

    public function destroy(int $id, BaseHttpResponse $response)
    {
        $enquiry = TourEnquiry::findOrFail($id);
        $enquiry->delete();
        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}


