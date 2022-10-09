<?php
$attributes = $attributes->class('py-5');
?>
<div {!! $attributes !!}>
    <div class="container">
        {!! $slot or '' !!}
    </div>
</div>
