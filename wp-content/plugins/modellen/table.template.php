<!-- Category: {cat} | Index: {ndx} -->
<a name="{hashtitle}" />
<div class=noshop-product>
	<div class="ph">
        
            <img class=noshop-product-image {widthparam} src="{imgurl}" />
     	
                        
       
                        <div class=noshop-product-imagebox {widthparam}>
                        {t1-begin}<img  class="thumbimg1"src="{timgurl1}" />{t1-end}
                        </div>
                        <div class=noshop-product-imagebox {widthparam}>
                        {t2-begin}<img  class="thumbimg2"src="{timgurl2}" />{t2-end}
                        </div>
                        <div class=noshop-product-imagebox {widthparam}>
                        {t3-begin}<img  class="thumbimg3"src="{timgurl3}" />{t3-end}
                        </div>
	</div>

	<div class=noshop-product-textbox style="margin-left: {width}px;">
		<!-- <div class=noshop-product-title>{title}</div> -->
		<div class=noshop-product-title>{title}</div>
		<div class=noshop-product-desc>
			<p>{description}</p>
			
			<?PHP $c='{count}' ; ?>
		</div>
        
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <tr>
            <td  >
			<div class="kader"  > 
            <?php include 'maattabel.php'; ?> 
			
   			</div>
           
            <div>
           {subtable} 
			</div>
           </td>
          </tr>
        </table>




		
	</div>
</div>