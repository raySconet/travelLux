<p>Dear {{ $user->fname }} {{ $user->lname }},</p>

<p>You requested to reset your password.</p>

<p>Email:<strong>{{ $user->email }}</strong></p>

<p>
    <a href="{{ route('password.reset',$token) }}">
        Reset Password
    </a>
</p>

<p style="color:red;">This link expires in 10 minutes.</p>