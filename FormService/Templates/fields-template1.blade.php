@if($config->plain != true)

@if(isset($config->cssClass))
<div class="{{$config->cssClass}}">
@else
<div class="col-md-12">
@endif



  <div class="form-group ">
    <label for="{{ $config->name }}" 
           class="col-sm-4 control-label"
           style="text-align: left;"
           >{{ ucfirst((isset($config->label)?$config->label:str_replace('_', ' ', $config->name))) }}: </label>
    <div class="col-sm-8" >
@endif
        
        @if($config->type == 'text' || $config->type == 'number')
        <input type="<?= $config->type ?>" class="form-control" ng-model="<?= 'form.'.$config->name ?>">

        @endif
        
         @if($config->type == 'date')
        <input type="<?= $config->type ?>" class="form-control" ng-model="<?= 'form.'.$config->name ?>">
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
            <select  name="<?= $config->name ?>" class="form-control" ng-model="<?= 'form.'.$config->name ?>">
            @foreach($config->values as $key => $value)
       
                  <option ng-value="<?= $key ?>"><?= $value ?></option>
  
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