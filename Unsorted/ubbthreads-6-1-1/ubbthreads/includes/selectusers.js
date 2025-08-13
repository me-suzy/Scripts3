<SCRIPT TYPE="text/javascript" LANGUAGE="Javascript1.1">
<!-- hide javascript from older browsers
var empty = new Array(
  "('Choose Value...','')",
  "('-------------------------------------','')",
  "('Please choose a search by category','')",
  "('first, then choose a value.','')"
);

var usersArray = new Array (
  "('All Users','All Users')",
  "('A','A')",
  "('B','B')",
  "('C','C')",
  "('D','D')",
  "('E','E')",
  "('F','F')",
  "('G','G')",
  "('H','H')",
  "('I','I')",
  "('J','J')",
  "('K','K')",
  "('L','L')",
  "('M','M')",
  "('N','N')",
  "('O','O')",
  "('P','P')",
  "('Q','Q')",
  "('R','R')",
  "('S','S')",
  "('T','T')",
  "('U','U')",
  "('V','V')",
  "('W','W')",
  "('X','X')",
  "('Y','Y')",
  "('Z','Z')",
  "('0-9','0-9')"
);
var totpostsArray = new Array (
  "('0-25','0-25')",
  "('26-50','26-50')",
  "('51-100','51-100')",
  "('101-200','101-200')",
  "('201-400','201-400')",
  "('401-700','401-700')",
  "('701-1200','701-1200')",
  "('1201-1600','1201-1600')",
  "('1601-2500','1601-2500')",
  "('2500+','2500+')"
);
var dateregArray = new Array (
  "('1 Day','86400')",
  "('2 Days','172800')",
  "('1 Week','604800')",
  "('2 Weeks','1209600')",
  "('3 Weeks','1814400')",
  "('1 Month','2628000')",
  "('3 Months','7884000')",
  "('6 Months','15768000')",
  "('1 Year','31536000')"
)
var lastonArray = new Array (
  "('1 Day','86400')",
  "('2 Days','172800')",
  "('1 Week','604800')",
  "('2 Weeks','1209600')",
  "('3 Weeks','1814400')",
  "('1 Month','2628000')",
  "('3 Months','7884000')",
  "('6 Months','15768000')",
  "('1 Year','31536000')"
);
var usernameArray = new Array (
  "('Enter Username below','')"
);
var realusernameArray = new Array (
  "('Enter Real Name below','')"
);
var groupArray = new Array (
  "('Select the Group you want to view from the menu below','')"
);
var domainArray = new Array (
  "('Enter the Domain below','')"
);
var emailArray = new Array (
  "('Enter the E-mail address below','')"
);

function insertProduct(category,product)
{
  if (product == '')
  { product = 'empty';
  }
  else if (product == 'username' || product == 'totposts' || product == 'domain'|| product == 'email'|| product == 'realusername'
           || product == 'datereg' || product == 'laston' || product == 'users' || product == 'group')
  {
         while (eval(product + 'Array.length') < category.options.length)
        {
             category.options[(category.options.length - 1)] = null;
        }
        for (var i=0; i < eval(product + 'Array.length'); i++)
        {
             eval("category.options[i] = new Option" + eval(product + 'Array[i]'));
        }
   }
   document.myForm.Menu2.selectedIndex =0;

}
// end javascript hiding -->
</SCRIPT>
