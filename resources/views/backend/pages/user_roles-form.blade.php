@extends('backend.layouts.master')

@section('page_title')
User Role
@endsection

@section('content')

<div class="container">

    <div class="row">

        <div class="col">

            @if ($vars['form_type'] == 'create')
            <h1>New user role</h1>
            @endif
            @if ($vars['form_type'] == 'edit')
            <h1>Edit user role</h1>
            @endif

            @include('backend.partials.errors')

            <form method="post" action="{{ $vars['action'] }}">
                {!! csrf_field() !!}
                <input type="hidden" name="id" value="{{ $vars['id'] }}" />

                <div class="form-group row">
                    <label for="name" class="col-2 col-form-label">Name</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('name',$vars['user_role']->name) }}" id="name" name="name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sort_order" class="col-2 col-form-label">Sort order</label>
                    <div class="col-10">
                        <input class="form-control" type="text" value="{{ old('sort_order',$vars['user_role']->sort_order) }}" id="sort_order" name="sort_order">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="enabled" class="col-2">Enabled</label>
                    <div class="col-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="enabled" value="1" {{ old('enabled',$vars['user_role']->enabled) === 1 ? 'checked' : '' }}> Yes
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="capabilities" class="col-2">Capabilities</label>
                    <div class="col-10">
                        <div id="accordion" role="tablist" aria-multiselectable="true">
                        <?php
                            $group = -1;
                            $set = false;
                        ?>
                        @foreach($vars['capabilities'] as $item)
                        <?php
                            if ($group == -1) {
                                $group = $item->sort_order;
                                $set = false;
                            }
                            if ($group != $item->sort_order) {
                                $group = $item->sort_order;
                                $set = false;
                            }
                            if (!$set && $group == 0) {
                                $set = true;
                                echo '<div class="card"><div class="card-header" role="tab" id="headingOne"><h5><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Users</a></h5></div><div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                  <div class="card-block">';
                            } elseif (!$set && $group == 1) {
                                $set = true;
                                echo '</div></div></div><div class="card"><div class="card-header" role="tab" id="headingTwo"><h5><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">User roles</a></h5></div><div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                                  <div class="card-block">';
                            } elseif (!$set && $group == 2) {
                                $set = true;
                                echo '</div></div></div><div class="card"><div class="card-header" role="tab" id="headingThree"><h5><a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Settings</a></h5></div><div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                                  <div class="card-block">';
                            } elseif (!$set && $group == 3) {
                                $set = true;
                                echo '</div></div></div><div class="card"><div class="card-header" role="tab" id="headingFour"><h5><a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Cache</a></h5></div><div id="collapseFour" class="collapse" role="tabpanel" aria-labelledby="headingFour">
                                  <div class="card-block">';
                            } elseif (!$set && $group == 4) {
                                $set = true;
                                echo '</div></div></div><div class="card"><div class="card-header" role="tab" id="headingFive"><h5><a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">Backups</a></h5></div><div id="collapseFive" class="collapse" role="tabpanel" aria-labelledby="headingFive">
                                  <div class="card-block">';
                            } elseif (!$set && $group == 5) {
                                $set = true;
                                echo '</div></div></div><div class="card"><div class="card-header" role="tab" id="headingSix"><h5><a data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">Capabilities</a></h5></div><div id="collapseSix" class="collapse" role="tabpanel" aria-labelledby="headingSix">
                                  <div class="card-block">';
                            } elseif (!$set && $group == 6) {
                                $set = true;
                                echo '</div></div></div><div class="card"><div class="card-header" role="tab" id="headingSeven"><h5><a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">Misc</a></h5></div><div id="collapseSeven" class="collapse" role="tabpanel" aria-labelledby="headingSeven">
                                  <div class="card-block">';
                            } ?>

                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="capabilities[]" value="{{ $item->id }}" {{ in_array($item->id,$vars['user_role']->capabilities->pluck('id')->toArray()) ? 'checked' : '' }}> {{ $item->name }}
                                        </label>
                                    </div>
                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection
