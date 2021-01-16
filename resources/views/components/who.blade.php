@if (Auth::guard('web')->check())
<p class="text-success">
  You are Logged In as a <strong>USER : {{ Auth::User()->name }}</strong>
</p>
@else
<p class="text-danger">
  You are Logged Out as a <strong>USER</strong>
</p>
@endif

@if (Auth::guard('doctor')->check())
<p class="text-success">
  You are Logged In as a <strong>Doctor   </strong>
</p>
@else
<p class="text-danger">
  You are Logged Out as a <strong>Doctor</strong>
</p>
@endif
@if (Auth::guard('assistant')->check())
<p class="text-success">
  You are Logged In as a <strong>Assistant   </strong>
</p>
@else
<p class="text-danger">
  You are Logged Out as a <strong>Assistant</strong>
</p>
@endif
@if (Auth::guard('student')->check())
<p class="text-success">
  You are Logged In as a <strong>Student  </strong>
</p>
@else
<p class="text-danger">
  You are Logged Out as a <strong>Student</strong>
</p>
@endif