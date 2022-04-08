<template>
    <div>
        <!-- <div>
            <span
            @mouseover="hover = true"
            @mouseleave="hover = false"
            >
            Hover me to show the message!
            </span>
            <span v-if="hover">This is a secret message.</span>
        </div>
        <div>
        I'm an example componentss.
        </div> -->
        <!-- {{colors}} -->
        <center>
        <!-- <h1>display colorss</h1> -->
        <div style="overflow-x:auto;">
            <table>
            <tr v-if="showCircle">
                <!-- <td v-if="clickedSquare"><span class="circle" v-bind:style = "{background: displayClickedColor}"></span></td> -->
                <td v-for="col in colors" :key="col.id">
                    <span 
                    @click = "clickIcon(col)"
                    @mouseover="hoverOverCircle(col)"
                    @mouseleave="hoverLeaveCircle(col)"                    
                    v-bind:class="col.type" v-bind:style="[currentCircleColorId == col.id || currentCircleColorId == '' ? { background: col.color,  opacity: 1} : {background: col.color,  opacity: 0.5}]">
                    </span><br>
                    <span v-if="showCircleColorText">
                        <p v-if="currentCircleColorId == col.id">{{col.color}}</p>
                    </span>
                </td>
            </tr>
            
            <tr v-if="showSquare">
                <!-- <h1>Display square</h1> -->
                <td v-for="color in squarecolors" :key="color.id">
                    <span v-if="showSquareColorText">
                        <p v-if="currentSquareColorId == color.id">{{color.color}}</p>
                    </span><br>
                    <span 
                    @click = "clickSquareIcon(color)"
                    @mouseover="hoverOverSquare(color)"
                    @mouseleave="hoverLeaveSquare(color)"                    
                    v-bind:class="color.type" v-bind:style="[currentSquareColorId == '' || currentSquareColorId == color.id ? { background: color.color,  opacity: 1} : {background: color.color,  opacity: 0}]">
                    </span>
                    
                </td>
            </tr>
            
            </table>
        </div>
        <div>
            <p>Made with: laravel, vue js, html, css</p>
        </div>
        </center>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mountedss.')
        },
         data() {
            return {
            showCircle: true,
            showCircleColorText: false,
            currentCircleColorId: '',
            
            showSquare: false,
            showSquareColorText: false,
            currentSquareColorId: '',
            
            clickedSquare: false,
            displayClickedColor: '',
            colors: [{id : 1, type:'circle', color: 'Red'}, 
                    {id : 2, type:'circle', color: 'Green'},
                    {id : 3, type:'circle', color: 'Blue'},
                    {id : 4, type:'circle', color: 'Orange'},
                    {id : 5, type:'circle', color: 'Yellow'}         
                ],
            squarecolors:[{id : 1, type:'square', color: 'Red'}, 
                    {id : 2, type:'square', color: 'Green'},
                    {id : 3, type:'square', color: 'Blue'},
                    {id : 4, type:'square', color: 'Orange'},
                    {id : 5, type:'square', color: 'Yellow'}         
                ], 
            
            // colors: ['Red','Green','Blue','Orange','Yellow'],
            // squarecolors:['Red','Green','Blue','Orange','Yellow'],   

            };
            
            
        },
        methods: {
            //hovering the circle
            hoverOverCircle: function(col){
                console.log(col)
                this.showCircleColorText = true
                this.currentCircleColorId = col.id
                // this.changeOpacity = 0.5
            },
            //To Leave the hovering circle
            hoverLeaveCircle: function(col){
                this.showCircleColorText = false
                this.currentCircleColorId = ''
                // this.changeOpacity = 1
            },
            //To click the circle and display the row of squares
            clickIcon: function(color){
                console.log('clicked the Icon')
                this.showSquare = true
            },
            //hovering the square
            hoverOverSquare: function(color){
                console.log(color)
                this.showSquareColorText = true
                this.currentSquareColorId = color.id
                console.log('color id', this.currentSquareColorId)
            },
            //To Leave the hovering square
            hoverLeaveSquare: function(color){
                this.showSquareColorText = false
                this.currentSquareColorId = ''
                // this.changeOpacity = 1
            },
            //To click the square icon and become a circle and placed first in the circle row
            clickSquareIcon: function(color){
                console.log('show color', color)
                // this.showCircle = false
                // this.showSquare = false

                this.clickedSquare = true
                var countData = this.colors.length
                var new_id = countData+1

                let temp = []
                this.squarecolors.forEach(element => {
                    console.log('color element', element)
                    if (element.id == color.id) {
                        temp.push({id: element.id , type:'circle', color: element.color})
                    }else{
                        temp.push({id: element.id , type: element.type, color: element.color})
                    }
                });
                console.log('Square colors temp',temp)
                this.squarecolors = temp
                console.log('new id', new_id)
                var new_color_data = {id: new_id, type:'circle', color: color.color}
                this.colors.unshift(new_color_data)
                // this.squarecolors.sort()
                // this.colors.push(color)
                console.log('circle colors', this.colors)
            }
        }
    }
</script>

<style>
/* body{
	margin:0;
	padding:20px;
	font-family: sans-serif;
} */

*{
	box-sizing: border-box;
}

.table{
	width: 100%;
	border-collapse: collapse;
}

.table td,.table th{
  padding:12px 15px;
  border:1px solid #ddd;
  text-align: center;
  font-size:16px;
}

.table th{
	background-color: darkblue;
	color:#ffffff;
}

.table tbody tr:nth-child(even){
	background-color: #f5f5f5;
}

/*responsive*/

@media(max-width: 500px){
	.table thead{
		display: none;
	}

	.table, .table tbody, .table tr, .table td{
		display: block;
		width: 100%;
	}
	.table tr{
		margin-bottom:15px;
	}
	.table td{
		text-align: right;
		padding-left: 50%;
		text-align: right;
		position: relative;
	}
	.table td::before{
		content: attr(data-label);
		position: absolute;
		left:0;
		width: 50%;
		padding-left:15px;
		font-size:15px;
		font-weight: bold;
		text-align: left;
	}
}

/* table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
} */

/* th, td {
  text-align: left;
  padding: 0px;
} */


.circle{
  height: 50px;
  width: 50px;
  border-radius: 50%;
  display: inline-block;
  
}

.square{
   height: 50px;
  width: 50px;
  display: inline-block;
  
}


</style>