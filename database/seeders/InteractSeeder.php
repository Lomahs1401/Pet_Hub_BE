<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Interact;
use Illuminate\Database\Seeder;

class InteractSeeder extends Seeder
{
    private $customer_accounts;
    private $interacted_accounts_of_blog = [];
    private $interacted_accounts_of_comment = [];
    private $interacted_accounts_of_account = [];

    public function __construct()
    {
        // Lấy danh sách các tài khoản customer hiện có trong cơ sở dữ liệu
        $this->customer_accounts = Account::whereHas('roles', function ($query) {
            $query->where('role_type', 'Customer');
        })->get();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogs = Blog::all();
        $comments = Comment::all();

        foreach ($blogs as $blog) {
            $this->fakeBlogInteractions($blog);
        }

        foreach ($comments as $comment) {
            $this->fakeCommentInteractions($comment);
        }

        foreach ($this->customer_accounts as $customer_account) {
            $this->fakeAccountInteractions($customer_account);
        }
    }

    private function fakeBlogInteractions($blog)
    {
        // Khởi tạo mảng tài khoản tương tác cho mỗi bài blog
        $this->interacted_accounts_of_blog[$blog->id] = [];
    
        // Random số lượng likes, dislikes, saves và shares
        $number_of_likes = rand(0, 10);
        $number_of_dislikes = rand(0, 5);
        $number_of_saves = rand(0, 2);
        $number_of_shares = rand(0, 2);

        for ($i = 0; $i < $number_of_likes; $i++) {
            // Lấy ngẫu nhiên một tài khoản từ danh sách
            $customer_account = $this->customer_accounts->random();
            // Kiểm tra xem tài khoản đã tương tác với blog chưa
            if (!in_array($customer_account->id, $this->interacted_accounts_of_blog[$blog->id])) {
                // Tạo một tương tác mới
                $this->createInteraction($blog, $customer_account, 'like');
                // Đánh dấu tài khoản đã tương tác với blog
                $this->interacted_accounts_of_blog[$blog->id][] = $customer_account->id;
            }
        }

        $this->interacted_accounts_of_blog[$blog->id] = [];

        for ($i = 0; $i < $number_of_dislikes; $i++) {
            // Lấy ngẫu nhiên một tài khoản từ danh sách
            $customer_account = $this->customer_accounts->random();
            // Kiểm tra xem tài khoản đã tương tác với blog chưa
            if (!in_array($customer_account->id, $this->interacted_accounts_of_blog[$blog->id])) {
                // Tạo một tương tác mới
                $this->createInteraction($blog, $customer_account, 'dislike');
                // Đánh dấu tài khoản đã tương tác với blog
                $this->interacted_accounts_of_blog[$blog->id][] = $customer_account->id;
            }
        }

        $this->interacted_accounts_of_blog[$blog->id] = [];

        for ($i = 0; $i < $number_of_saves; $i++) {
            // Lấy ngẫu nhiên một tài khoản từ danh sách
            $customer_account = $this->customer_accounts->random();
            // Kiểm tra xem tài khoản đã tương tác với blog chưa
            if (!in_array($customer_account->id, $this->interacted_accounts_of_blog[$blog->id])) {
                // Tạo một tương tác mới
                $this->createInteraction($blog, $customer_account, 'save');
                // Đánh dấu tài khoản đã tương tác với blog
                $this->interacted_accounts_of_blog[$blog->id][] = $customer_account->id;
            }
        }

        $this->interacted_accounts_of_blog[$blog->id] = [];

        for ($i = 0; $i < $number_of_shares; $i++) {
            // Lấy ngẫu nhiên một tài khoản từ danh sách
            $customer_account = $this->customer_accounts->random();
            // Kiểm tra xem tài khoản đã tương tác với blog chưa
            if (!in_array($customer_account->id, $this->interacted_accounts_of_blog[$blog->id])) {
                // Tạo một tương tác mới
                $this->createInteraction($blog, $customer_account, 'share');
                // Đánh dấu tài khoản đã tương tác với blog
                $this->interacted_accounts_of_blog[$blog->id][] = $customer_account->id;
            }
        }

        $this->interacted_accounts_of_blog[$blog->id] = [];
    }

    private function fakeCommentInteractions($comment)
    {
        // Khởi tạo mảng tài khoản tương tác cho mỗi bài comment
        $this->interacted_accounts_of_comment[$comment->id] = [];

        $number_of_likes = rand(0, 6);
        $number_of_dislikes = rand(0, 2);

        for ($i = 0; $i < $number_of_likes; $i++) {
            // Lấy ngẫu nhiên một tài khoản từ danh sách
            $customer_account = $this->customer_accounts->random();
            // Kiểm tra xem tài khoản đã tương tác với blog chưa
            if (!in_array($customer_account->id, $this->interacted_accounts_of_comment[$comment->id])) {
                // Tạo một tương tác mới
                $this->createInteraction($comment, $customer_account, 'like');
                // Đánh dấu tài khoản đã tương tác với blog
                $this->interacted_accounts_of_comment[$comment->id][] = $customer_account->id;
            }
        }

        $this->interacted_accounts_of_comment[$comment->id] = [];

        for ($i = 0; $i < $number_of_dislikes; $i++) {
            // Lấy ngẫu nhiên một tài khoản từ danh sách
            $customer_account = $this->customer_accounts->random();
            // Kiểm tra xem tài khoản đã tương tác với blog chưa
            if (!in_array($customer_account->id, $this->interacted_accounts_of_comment[$comment->id])) {
                // Tạo một tương tác mới
                $this->createInteraction($comment, $customer_account, 'dislike');
                // Đánh dấu tài khoản đã tương tác với blog
                $this->interacted_accounts_of_comment[$comment->id][] = $customer_account->id;
            }
        }

        $this->interacted_accounts_of_comment[$comment->id] = [];
    }

    private function fakeAccountInteractions($account)
    {
        // Khởi tạo mảng tài khoản tương tác cho mỗi account
        $this->interacted_accounts_of_account[$account->id] = [];

        $number_of_follow = rand(0, 5);

        for ($i = 0; $i < $number_of_follow; $i++) {
            // Lấy ngẫu nhiên một tài khoản từ danh sách
            $customer_account = $this->customer_accounts->random();
            // Kiểm tra xem tài khoản đã tương tác với blog chưa
            if (!in_array($customer_account->id, $this->interacted_accounts_of_account[$account->id])) {
                // Tạo một tương tác mới
                $this->createInteraction($account, $customer_account, 'follow');
                // Đánh dấu tài khoản đã tương tác với blog
                $this->interacted_accounts_of_account[$account->id][] = $customer_account->id;
            }
        }

        $this->interacted_accounts_of_account[$account->id] = [];
    }
    
    private function createInteraction($target, $account, $interaction_type)
    {
        // Kiểm tra loại target và thực hiện các thao tác tương ứng
        if ($target instanceof Blog) {
            $this->interacted_accounts_of_blog[$target->id][] = $account->id;
        } elseif ($target instanceof Comment) {
            $this->interacted_accounts_of_comment[$target->id][] = $account->id;
        } elseif ($target instanceof Account) {
            $this->interacted_accounts_of_account[$target->id][] = $account->id;
        }

        Interact::factory()->create([
            'target_label' => $target->getTable(),
            'target_type' => $interaction_type,
            'target_id' => $target->id,
            'account_id' => $account->id,
        ]);
    }
}
