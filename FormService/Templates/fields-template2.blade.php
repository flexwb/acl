@if($config->plain != true)

@if(isset($config->cssClass))
<div class="{{$config->cssClass}}">
@else
<div class="col-md-12">
@endif


  <div class="form-group ">
    <label for="{{ $config->name }}" 
           class="{{$config->cssLabel}} control-label"
           style="text-align: left;"
           >{{ ucfirst((isset($config->label)?$config->label:str_replace('_', ' ', $config->name))) }}: </label>
    <div class="{{$config->cssField}}" >
@endif
        @if($config->type == 'texteditor')
            <div  summernote class="summernote" ng-model="<?= 'form.'.$config->name ?>"></div>
        @endif

        @if($config->type == 'text' || $config->type == 'number' || $config->type == 'password')
        <input type="<?= $config->type ?>" class="form-control" ng-model="<?= 'form.'.$config->name ?>">

        @endif
        
         @if($config->type == 'date')
        <mv-date-field field-value='<?= 'form.'.$config->name ?>'></mv-date-field>
        
         @endif
        
        
        @if($config->type == 'checkbox' || $config->type == 'radio')
        <div>
            @foreach($config->values as $key => $value)
                <span style="margin-right: 10px;">
                 <input type="<?= $config->type ?>" ng-model="<?= 'form.'.$config->name ?>"  
                        name="<?= $config->name ?>" value="<?= $key ?>">
                <?= ucfirst($value) ?></span>
           
            @endforeach
       </div>
        @endif

        @if($config->type == 'select')
            <select  
                     class="form-control" 
                     ng-model="<?= 'form.'.$config->name ?>"
                     >
            @foreach($config->values as $key => $value)
       
                  <option value="<?= $key ?>"><?= $value ?></option>
  
            @endforeach
            </select>
        @endif   
        
@if($config->plain != true)
    </div>
    <div class="col-sm-8 col-sm-offset-4">
        <div style="color:red;"> 
            {{ <?= $config->errorFieldPrefix ?>['<?= $config->errorField ?>'][0] }}</div>
    
    </div>

  </div>
</div>
@endif