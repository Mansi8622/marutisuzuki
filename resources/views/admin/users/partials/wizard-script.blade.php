<script>
$(function () {
  var form = $('#userWizard'); if (!form.length) return;
  var config = [
    { title:'Account details', note:'Set up the user identity and approval status.', fields:['name','email','phone','approved'] },
    { title:'Business details', note:'Add business, tax and address information.', fields:['business_name','business_type','gst_number','pan_number','business_address'] },
    { title:'Bank & vendor', note:'Enter payout details and the vendor identifier.', fields:['bank_name','account_number','ifsc_code','account_holder_name','license_details','status','vendor'] },
    { title:'Access & review', note:'Choose roles and secure the account before saving.', fields:['roles','password'] },
    { title:'Documents', note:'Upload KYC and registration documents before saving.', fields:['kyc_documents_front','kyc_documents_back','business_registration_certificate'] }
  ];
  var steps = $('<div class="wizard-steps"></div>'), panes = $('<div class="wizard-panes"></div>');
  config.forEach(function (section, index) {
    var step = $('<div class="wizard-step" data-step="'+(index+1)+'">'+section.title+'</div>').on('click', function(){ show(index); }); steps.append(step);
    var pane = $('<section class="wizard-pane"><h3>'+section.title+'</h3><p class="wizard-note">'+section.note+'</p></section>');
    section.fields.forEach(function(id){ var input=$('#'+id); if(input.length) pane.append(input.closest('.form-group')); });
    if(index===2) pane.append(form.find('button[onclick="generateVendorId()"]').detach());
    panes.append(pane);
  });
  var submit = form.find('button[type="submit"]').detach();
  form.prepend(steps).append(panes).append('<div class="wizard-actions"><button type="button" class="btn btn-default wizard-prev">Back</button><span class="wizard-progress"></span><button type="button" class="btn btn-primary wizard-next">Continue <i class="fa fa-arrow-right"></i></button></div>');
  form.find('.wizard-actions').append(submit.addClass('wizard-submit'));
  var current=0;
  function show(index){ current=index; form.find('.wizard-pane').removeClass('active').eq(index).addClass('active'); form.find('.wizard-step').removeClass('active done').each(function(i){$(this).toggleClass('active',i===index).toggleClass('done',i<index);}); form.find('.wizard-prev').toggle(index>0); form.find('.wizard-next').toggle(index<config.length-1); form.find('.wizard-submit').toggle(index===config.length-1); form.find('.wizard-progress').text('Step '+(index+1)+' of '+config.length); }
  form.on('click','.wizard-next',function(){ var pane=form.find('.wizard-pane').eq(current), valid=true; pane.find(':input[required]').each(function(){ if(!this.checkValidity()){this.reportValidity(); valid=false; return false;} }); if(valid)show(current+1); });
  form.on('click','.wizard-prev',function(){show(current-1);});
  var errorIndex=0; form.find('.has-error').each(function(){var i=form.find('.wizard-pane').index($(this).closest('.wizard-pane')); if(i>=0){errorIndex=i;return false;}}); show(errorIndex);
});
</script>
