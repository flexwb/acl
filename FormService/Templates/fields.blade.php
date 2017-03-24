@if($config->plain != true)

@if(isset($config->cssClass))
<div class="{{$config->cssClass}}">
@else
<div class="col-md-12">
@endif


  <div class="form-group row">
    <label for="{{ $config->name }}" 
           class="{{$config->cssLabel}} col-2 col-form-label"
           style="text-align: left;"
           >{{ ucfirst((isset($config->label)?$config->label:str_replace('_', ' ', $config->name))) }}: </label>
      <div class="col-10 {{$config->cssField}}">
@endif
        @if($config->type == 'texteditor')
            <div  summernote class="summernote" ng-model="<?= 'home.form.'.$config->name ?>"></div>
        @endif
        
        @if($config->type == 'textarea')
            <textarea class="form-control" rows="3" ng-model="<?= 'home.form.'.$config->name ?>"></textarea>
        @endif
        

        @if($config->type == 'text' || $config->type == 'number' || $config->type == 'password')
        <input type="<?= $config->type ?>" class="form-control" ng-model="<?= 'home.form.'.$config->name ?>">
        @endif
        
         @if($config->type == 'date')
        <mv-date-field field-value='<?= 'home.form.'.$config->name ?>'></mv-date-field>
         @endif
        
        @if($config->type == 'datepicker')
        <div>
        <input ng-flatpickr type="text" 
               ng-model="<?= 'home.form.'.$config->name ?>" 
               placeholder="Select Date.." />
        </div>
        @endif
        
        @if($config->type == 'checkbox' || $config->type == 'radio')
        <div>
            @foreach($config->values as $key => $value)
                <span style="margin-right: 10px;">
                 <input type="<?= $config->type ?>" ng-model="<?= 'home.form.'.$config->name ?>"  
                        name="<?= $config->name ?>" value="<?= $key ?>">
                <?= ucfirst($value) ?></span>
           
            @endforeach
       </div>
        @endif

        @if($config->type == 'file')
            @include('form_views::fileupload')
        @endif
        
        @if($config->type == 'select')
            <select  
                     class="form-control" 
                     ng-model="<?= 'home.form.'.$config->name ?>"
                     >
            @foreach($config->values as $key => $value)
       
                  <option ng-value="<?= $key ?>" value="<?= $key ?>"><?= $value ?></option>
  
            @endforeach
            </select>
        @endif   
        
@if($config->plain != true)
            <div style="color:red;" ng-if="<?= $config->errorFieldPrefix ?>['<?= $config->errorField ?>'].length > 0"> 
                <ul>
                    <li ng-repeat="error in <?= $config->errorFieldPrefix ?>['<?= $config->errorField ?>']">
                        @{{error}}
                    </li>
                </ul>
            </div>
    </div>

  </div>
</div>
@endif