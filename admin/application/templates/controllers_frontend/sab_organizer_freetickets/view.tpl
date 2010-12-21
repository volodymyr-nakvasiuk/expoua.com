<style>
{literal}
  ul {
    margin: 0;
    padding: 0;
    
    list-style: square;
  }
  
  
  ul li {
    margin: 0 0 2px 1.1em;
    padding: 0;
  }
  
  
  #photo {
    float: left; 
    
    margin: 0; 
    padding: 0; 
    
    width: 180px; 
    
    text-align: left; 
  }
  
  
  .mtable {
    width: 100%;
    
    font-size: 90%;
    
    border-top: 1px solid #E4EBEC;
  }
  
  .mtable td, .mtable th {
    padding: 4px 0;
    
    border-bottom: 1px solid #E4EBEC;
  }
  
  
  .mtable th {
    width: 25%;
    text-align: left;
    color: #999;
  }
{/literal}
</style>

<h2>{$userData.fname} {$userData.name}</h2>

<div>
  <div style="float:left; margin:0; padding:0; width:180px; text-align:left;">
    <img src="http://admin.expopromoter.com/data/images/user_sites/{$userData.id}.jpg?salt={$smarty.now}" alt="" />
  </div>
  
  <div style="margin:0 0 10px 220px;">
    <table class="mtable" cellspacing="0">
    <tr valign="top">
      <th>{#captionPersonalEmail#}</th>
      <td>{$userData.email}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionCompanyName#}</th>
      <td>{$userData.companyName}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionCountry#}</th>
      <td>{$userData.countryName}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionCity#}</th>
      <td>{$userData.cityName}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionPostalCode#}</th>
      <td>{$userData.zipcode}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionAddress#}</th>
      <td>{$userData.address}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionPhones#}</th>
      <td>{$userData.company_phone}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionCompanyEmail#}</th>
      <td>{$userData.company_email}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionWebAddress#}</th>
      <td>{$userData.webAddress}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionCompanyDescr#}</th>
      <td>{$userData.comment}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionPosition#}</th>
      <td>{$userData.text_dolgnost}</td>
    </tr>
  
    <tr valign="top">
      <th>{#captionStatus#}</th>
      <td>
        {if $userData.status == 1}{#captionStatus1#}{/if}
        {if $userData.status == 2}{#captionStatus2#}{/if}
        {if $userData.status == 3}{#captionStatus3#}{/if}
        {if $userData.status == 4}{#captionStatus4#}{/if}
        {if $userData.status == 5}{#captionStatus5#}{/if}
        {if $userData.status == 6}{#captionStatus6#}{/if}
        {if $userData.status == 7}{#captionStatus7#}{/if}
        {if $userData.status == 8}{#captionStatus8#}{/if}
        {if $userData.status == 9}{#captionStatus9#}{/if}
        {if $userData.status == 10}{#captionStatus10#}{/if}
        {if $userData.status == 11}{#captionStatus11#}{/if}
        {if $userData.status == 12}{#captionStatus12#}{/if}
      </td>
    </tr>
  
    <tr valign="top"> 
      <th>{#captionFunction#}</th>
      <td>
        <ul>
          {if in_array(1, $userData.functions)}<li>{#captionFunction1#}</li>{/if}
          {if in_array(2, $userData.functions)}<li>{#captionFunction2#}</li>{/if}
          {if in_array(3, $userData.functions)}<li>{#captionFunction3#}</li>{/if}
          {if in_array(4, $userData.functions)}<li>{#captionFunction4#}</li>{/if}
          {if in_array(5, $userData.functions)}<li>{#captionFunction5#}</li>{/if}
          {if in_array(6, $userData.functions)}<li>{#captionFunction6#}</li>{/if}
          {if in_array(7, $userData.functions)}<li>{#captionFunction7#}</li>{/if}
          {if in_array(8, $userData.functions)}<li>{#captionFunction8#}</li>{/if}
          {if in_array(9, $userData.functions)}<li>{#captionFunction9#}</li>{/if}
          {if in_array(10, $userData.functions)}<li>{#captionFunction10#}</li>{/if}
          {if in_array(11, $userData.functions)}<li>{#captionFunction11#}</li>{/if}
          {if in_array(12, $userData.functions)}<li>{#captionFunction12#}</li>{/if}
          {if in_array(13, $userData.functions)}<li>{#captionFunction13#}</li>{/if}
          {if in_array(14, $userData.functions)}<li>{#captionFunction14#}</li>{/if}
          {if in_array(15, $userData.functions)}<li>{#captionFunction15#}</li>{/if}
          {if in_array(16, $userData.functions)}<li>{#captionFunction16#}</li>{/if}
        </ul>
      </td>
    </tr>
    </table>
  
  </div>
</div>

<p><a href="{getUrl controller=$user_params.controller parent=$user_params.parent action='list'}">{#linkBackToList#}</a></p>
