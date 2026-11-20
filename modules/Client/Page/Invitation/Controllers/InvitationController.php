<?php

namespace Modules\Client\Page\Invitation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Client\Page\Invitation\Models\InvitationModel;
use Modules\Client\Page\Invitation\Services\InvitationService;

/**
 * Thiệp cưới online: danh sách mẫu, xem mẫu, tạo thiệp, chỉnh sửa, trang thiệp công khai
 *
 * @author Luatnc
 */
class InvitationController extends Controller
{
    private InvitationService $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    /**
     * Danh sách mẫu thiệp
     */
    public function templates()
    {
        $data['templates'] = $this->invitationService->templates();
        $data['sections'] = config('landing.sections');
        return view('client.invitation.templates', $data);
    }

    /**
     * Xem trước một mẫu với dữ liệu và ảnh mẫu
     */
    public function preview(string $template)
    {
        abort_if(!$this->invitationService->template($template), 404);
        $sample = config('invitation.defaults');
        $sample['photos'] = config('invitation.sample_photos');
        $data = $this->invitationService->viewData($template, $sample, ['isSample' => true]);
        return view('client.invitation.templates.' . $data['template']['view'], $data);
    }

    /**
     * Tạo thiệp mới từ mẫu rồi chuyển sang trang chỉnh sửa
     */
    public function store(Request $request)
    {
        $template = (string) $request->input('template');
        abort_if(!$this->invitationService->template($template), 404);
        $invitation = $this->invitationService->create($template);
        return redirect('/thiep/sua/' . $invitation->edit_token);
    }

    /**
     * Trình chỉnh sửa: form bên trái, xem trước bên phải
     */
    public function edit(string $token)
    {
        $invitation = $this->findByToken($token);
        $data = $this->invitationService->viewData($invitation->template, $invitation->data, ['invitation' => $invitation]);
        $data['templates'] = $this->invitationService->templates();
        $data['rsvps'] = $invitation->rsvps()->get();
        return view('client.invitation.editor', $data);
    }

    /**
     * Nội dung khung xem trước trong trình chỉnh sửa
     */
    public function editPreview(string $token)
    {
        $invitation = $this->findByToken($token);
        $data = $this->invitationService->viewData($invitation->template, $invitation->data, [
            'invitation' => $invitation,
            'wishes' => $invitation->wishes()->take(50)->get(),
            'editing' => true,
        ]);
        return view('client.invitation.templates.' . $data['template']['view'], $data);
    }

    /**
     * Lưu nội dung thiệp (gọi bằng AJAX, tự lưu)
     */
    public function update(Request $request, string $token)
    {
        $invitation = $this->findByToken($token);
        $template = (string) $request->input('template', $invitation->template);
        if ($this->invitationService->template($template)) {
            $invitation->template = $template;
        }
        $invitation->data = $this->invitationService->normalize((array) $request->input('data', []), $invitation);
        $invitation->save();
        return response()->json(['success' => true, 'data' => $invitation->data]);
    }

    /**
     * Upload ảnh (bìa, album, mã QR)
     */
    public function upload(Request $request, string $token)
    {
        $invitation = $this->findByToken($token);
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:' . config('invitation.max_upload_kb'),
        ], [
            'photo.required' => 'Vui lòng chọn ảnh.',
            'photo.image' => 'Tệp tải lên phải là ảnh.',
            'photo.mimes' => 'Chỉ hỗ trợ ảnh JPG, PNG, WEBP.',
            'photo.max' => 'Ảnh tối đa ' . (config('invitation.max_upload_kb') / 1024) . 'MB.',
        ]);
        $path = $this->invitationService->storePhoto($invitation, $request->file('photo'));
        return response()->json(['success' => true, 'path' => $path, 'url' => url($path)]);
    }

    /**
     * Trang thiệp công khai gửi cho khách
     */
    public function show(string $slug)
    {
        $invitation = InvitationModel::where('slug', $slug)->firstOrFail();
        $data = $this->invitationService->viewData($invitation->template, $invitation->data, [
            'invitation' => $invitation,
            'wishes' => $invitation->wishes()->take(50)->get(),
        ]);
        return view('client.invitation.templates.' . $data['template']['view'], $data);
    }

    /**
     * Khách gửi lời chúc vào sổ lưu bút
     */
    public function wish(Request $request, string $slug)
    {
        $invitation = InvitationModel::where('slug', $slug)->firstOrFail();
        $input = $request->validate([
            'name' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'Vui lòng nhập tên của bạn.',
            'message.required' => 'Vui lòng nhập lời chúc.',
        ]);
        $wish = $invitation->wishes()->create($input);
        return response()->json([
            'success' => true,
            'wish' => ['name' => $wish->name, 'message' => $wish->message, 'time' => $wish->created_at->format('H:i d/m/Y')],
        ]);
    }

    /**
     * Khách xác nhận tham dự
     */
    public function rsvp(Request $request, string $slug)
    {
        $invitation = InvitationModel::where('slug', $slug)->firstOrFail();
        $input = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'attending' => 'required|boolean',
            'guests' => 'nullable|integer|min:1|max:20',
            'side' => 'nullable|in:nha-trai,nha-gai',
            'note' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập tên của bạn.',
        ]);
        $invitation->rsvps()->create($input + ['guests' => 1]);
        return response()->json(['success' => true]);
    }

    private function findByToken(string $token): InvitationModel
    {
        return InvitationModel::where('edit_token', $token)->firstOrFail();
    }
}
