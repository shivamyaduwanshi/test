<?php

namespace App\Http\Controllers\Api\User;

use Hash;
use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Models\Category;
use App\Models\Item;
use App\Models\Restaurant;
use App\Models\Rating;
use App\Models\FavouriteItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use Response;
use DB;
use App\Mail\NotifyMail;
use Mail;
use App;

class HomeController extends Controller
{
    protected $guard = 'api';
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
     public function __construct(Request $request){
         if($request->lang){
             App::setlocale($request->lang);
         }
     }

     public function home(Request $request){
        $categories = array();
        $popularitems = array();
        $restaurants  = array();
        $itemForYou   = array();
        $topRatedItems  = array();
        $categoriesData = Category::where('status','1')->whereNull('deleted_at')->orderBy('title','asc')->get();
        if($categoriesData->toarray()){
            foreach($categoriesData as $key => $category){
                array_push($categories,[
                    'category_id'    => $category->id,
                    'category_title' => $category->title,
                    'category_image' => $category->image
                ]);
            }
        }
        $popularItemdata = Item::where('status','1')->whereNull('deleted_at')->orderBy('id','desc')->limit(10)->get();
        if($popularItemdata->toarray()){
            foreach($popularItemdata as $key => $item){
                array_push($popularitems,[
                    'item_id'       => $item->id,
                    'restaurant_id' => $item->restaurant_id,
                    'title'         => $item->title,
                    'description'   => $item->description,
                    'price'         => $item->price,
                    'rating'        => '4',
                    'total_rating'    => '100',
                    'offer_price'   => $item->offer_price,
                    'image'         => $item->image,
                    'is_wishlist'   => '0',
                ]);
            }
        }
        $restaurantsData = Restaurant::where('status','1')->whereNull('deleted_at')->orderBy('id','desc')->limit(10)->get();
        if($restaurantsData->toarray()){
            foreach($restaurantsData as $key => $restaurant){
                array_push($restaurants,[
                    'restaurant_id'   => $restaurant->id,
                    'restaurant_name' => $restaurant->restaurant_name ?? '',
                    'owner_name'      => $restaurant->owner_name ?? '',
                    'email'           => $restaurant->email ?? '',
                    'phone'           => $restaurant->phone ?? '',
                    'profile_image'   => $restaurant->profile_image,
                    'address'         => $restaurant->address ?? '',
                    'lat'             => $restaurant->lat ?? '',
                    'lng'             => $restaurant->lng  ?? '',
                    'open_time'       => $restaurant->open_time  ?? '',
                    'close_time'      => $restaurant->close_time ?? '',
                    'status'          => $restaurant->status  ?? 'en',
                    'delivery_type'   => $restaurant->delivery_type,
                    'rating'          => '5',   
                    'total_rating'    => '100',
                    'off'             => '30'
                ]);
            }
        }
        $topRatedItemdata = Item::where('status','1')->whereNull('deleted_at')->orderBy('id','desc')->limit(10)->get();
        if($topRatedItemdata->toarray()){
            foreach($topRatedItemdata as $key => $item){
                array_push($itemForYou,[
                    'item_id'       => $item->id,
                    'restaurant_id' => $item->restaurant_id,
                    'title'         => $item->title,
                    'description'   => $item->description,
                    'price'         => $item->price,
                    'rating'        => '4',
                    'total_rating'    => '100',
                    'offer_price'   => $item->offer_price,
                    'image'         => $item->image,
                    'is_wishlist'   => '0',
                ]);
            }
        }

        $foodForYouItemdata = Item::where('status','1')->whereNull('deleted_at')->orderBy('id','desc')->limit(10)->get();
        if($foodForYouItemdata->toarray()){
            foreach($foodForYouItemdata as $key => $item){
                array_push($topRatedItems,[
                    'item_id'       => $item->id,
                    'restaurant_id' => $item->restaurant_id,
                    'title'         => $item->title,
                    'description'   => $item->description,
                    'price'         => $item->price,
                    'rating'        => '4',
                    'total_rating'    => '100',
                    'offer_price'   => $item->offer_price,
                    'image'         => $item->image,
                    'is_wishlist'   => '0',
                ]);
            }
        }

        $data = [
           'categories' => $categories,
           'popular_items' => $popularitems,
           'restaurants'   => $restaurants,
           'item_for_you'  => $itemForYou,
           'top_rated_items' => $topRatedItems
        ];
        return ['status'=>true,'message'=>'Record found','data'=>$data];
    }

     /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     public function categories(Request $request){
        $categories = Category::whereNull('deleted_at')->orderBy('title','asc')->get();
        if($categories->toarray()){
            $data = array();
            foreach($categories as $key => $category){
                array_push($data,[
                    'category_id' => $category->id,
                    'category_title' => $category->title,
                    'category_image' => $category->image,
                ]);
            }
            return ['status'=>true,'message'=>'Record found','data'=>$data];
        }else{
            return ['status'=>false,'message'=>'Record not found'];
        }
    }

    public function popularItems(Request $request){
        $items = Item::where('status','1')->whereNull('deleted_at')->orderBy('id','desc')->get();
        if($items->toarray()){
               $data = array();
            foreach($items as $key => $item){
                array_push($data,[
                    'item_id'       => $item->id,
                    'restaurant_id' => $item->restaurant_id,
                    'title'         => $item->title,
                    'description'   => $item->description,
                    'price'         => $item->price,
                    'rating'        => '4',
                    'total_rating'    => '100',
                    'offer_price'   => $item->offer_price,
                    'image'         => $item->image,
                    'is_wishlist'   => '0',
                  ]);
            }
            return ['status'=>true,'message'=>'Record found','total_item'=>count($items),'data'=>$data];
        }
        return ['status'=>false,'message'=>'Record not found','total_item'=>'0'];
    }

    public function topRatedItems(Request $request){
        
        $items = Item::where('status','1')->whereNull('deleted_at')->orderBy('id','desc')->get();
        if($items->toarray()){
               $data = array();
            foreach($items as $key => $item){
                array_push($data,[
                    'item_id'       => $item->id,
                    'restaurant_id' => $item->restaurant_id,
                    'title'         => $item->title,
                    'description'   => $item->description,
                    'price'         => $item->price,
                    'rating'        => '4',
                    'total_rating'    => '100',
                    'offer_price'   => $item->offer_price,
                    'image'         => $item->image,
                    'is_wishlist'   => '0',
                ]);
            }
            return ['status'=>true,'message'=>'Record found','total_item'=>count($items),'data'=>$data];
        }
        return ['status'=>false,'message'=>'Record not found','total_item'=>'0'];
    }

    public function restaurants(Request $request){
        $restaurants = Restaurant::where(function($query) use($request){
                                    if($request->search){
                                        $query->whereRaw('LOWER(restaurant_name) like ?' , '%'.strtolower($request->search).'%');
                                    }
                                  })
                                  ->where('status','1')
                                  ->whereNull('deleted_at')
                                  ->orderBy('id','desc')
                                  ->get();
        if($restaurants->toarray()){
            $data = array();
            foreach($restaurants as $key => $restaurant){
                array_push($data,[
                    'restaurant_id'   => $restaurant->id,
                    'restaurant_name' => $restaurant->restaurant_name ?? '',
                    'owner_name'      => $restaurant->owner_name ?? '',
                    'email'           => $restaurant->email ?? '',
                    'phone'           => $restaurant->phone ?? '',
                    'profile_image'   => $restaurant->profile_image,
                    'address'         => $restaurant->address ?? '',
                    'lat'             => $restaurant->lat ?? '',
                    'lng'             => $restaurant->lng  ?? '',
                    'open_time'       => $restaurant->open_time  ?? '',
                    'close_time'      => $restaurant->close_time ?? '',
                    'status'          => $restaurant->status  ?? 'en',
                    'delivery_type'   => $restaurant->delivery_type,
                    'rating'          => '5',   
                    'total_rating'    => '100',
                    'off'             => '30'
                ]);
            }
            return ['status'=>true,'message'=>'Record found','total_restaurant'=>count($restaurants),'data'=>$data];
        }
        return ['status'=>false,'message'=>'Record not found','total_restaurant'=>'0'];
    }
    
    public function restaurantDetails(Request $request){

        $rules = [
             'restaurant_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        $restaurant = Restaurant::find($request->restaurant_id);
        if($restaurant){
            $data = array();
            $data['restaurant_id']   = $restaurant->id;
            $data['restaurant_name'] = $restaurant->restaurant_name ?? '';
            $data['owner_name']      = $restaurant->owner_name ?? '';
            $data['email']           = $restaurant->email ?? '';
            $data['phone']           = $restaurant->phone ?? '';
            $data['profile_image']   = $restaurant->profile_image;
            $data['address']         = $restaurant->address ?? '';
            $data['lat']             = $restaurant->lat ?? '';
            $data['lng']             = $restaurant->lng  ?? '';
            $data['open_time']       = $restaurant->open_time  ?? '';
            $data['close_time']      = $restaurant->close_time ?? '';
            $data['status']          = $restaurant->status  ?? '';
            $data['delivery_type']   = $restaurant->delivery_type;
            $data['rating']          = $restaurant->rating->avg('rating');
            $data['total_rating']    = count($restaurant->rating);
            $data['off']             = $restaurant->off;
            $data['reviews']         = array();
            if($restaurant->rating){
                 foreach($restaurant->rating as $key => $rating){
                     array_push($data['reviews'],[
                         'user_id'   => $rating->user_id,
                         'user_name' => $rating->user->name,
                         'user_profile_image' => $rating->user->profile_image,
                         'rating'    => $rating->rating,
                         'review'    => $rating->review,
                         'created_at' => date('Y-m-d H:i:s')
                     ]);
                 }
            }
            return ['status'=>true,'message'=>'Record found','data'=>$data];
        }
        return ['status'=>false,'message'=>'Record not found'];       
    }

    public function items(Request $request){

        $items = Item::select('items.*')
                     ->where(function($query) use ($request){
                    if($request->restaurant_id)
                       $query->where('restaurant_id',$request->restaurant_id);

                    if($request->category_id)
                       $query->where('category_id',$request->category_id);
        });

        if($request->popular)
             $items  = $items->rightJoin('order_items','items.id','=','order_items.item_id');

        if($request->low_to_high)
             $items = $items->orderBy('items.price','asc');
        elseif($request->high_to_low)
             $items = $items->orderBy('items.price','desc');
        elseif($request->rating_high_to_low)
             $items = $items->orderBy('items.price','desc');
        else
             $item  = $items->orderBy('items.id','desc');

        $items = $items->distinct('items.id')->get();

        if($items->toarray()){
            $data = array();
         foreach($items as $key => $item){
             array_push($data,[
                 'item_id'       => $item->id,
                 'restaurant_id' => $item->restaurant_id,
                 'title'         => $item->title,
                 'description'   => $item->description,
                 'price'         => $item->price,
                 'rating'        => '4',
                 'total_rating'    => '100',
                 'offer_price'   => $item->offer_price,
                 'image'         => $item->image,
                 'is_wishlist'   => '0',
               ]);
         }
         return ['status'=>true,'message'=>'Record found','total_item'=>count($items),'data'=>$data];
     }
     return ['status'=>false,'message'=>'Record not found','total_item'=>'0'];

    }

    public function itemDetails(Request $request){
         $rules = [
            'item_id' => 'required',
         ];
       $validator = Validator::make($request->all(), $rules);
       if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
       }

         $item = Item::where('id',$request->item_id)->where('status','1')->whereNull('deleted_at')->first();
         if($item){
                    $data['item_id']       = $item->id;
                    $data['restaurant_id'] = $item->restaurant_id;
                    $data['restaurant_name'] = $item->restaurant->restaurant_name;
                    $data['category_id']   = $item->category_id;
                    $data['category_name'] = $item->category->title ?? '';
                    $data['title']         = $item->title;
                    $data['description']   = $item->description;
                    $data['price']         = $item->price;
                    $data['rating']        = $item->restaurant->rating->avg('rating');
                    $data['total_rating']  = count($item->restaurant->rating);
                    $data['offer_price']   = $item->offer_price;
                    $data['image']         = $item->image;
                    $data['is_wishlist']   = $item->isfavourite($request->user_id);
                    $data['restaurant']    = $item->restaurant;
                    $data['reviews']         = array();
                    if($item->restaurant->rating){
                         foreach($item->restaurant->rating as $key => $rating){
                             array_push($data['reviews'],[
                                 'user_id'   => $rating->user_id,
                                 'user_name' => $rating->user->name,
                                 'user_profile_image' => $rating->user->profile_image,
                                 'rating'    => $rating->rating,
                                 'review'    => $rating->review,
                                 'created_at' => date('Y-m-d H:i:s')
                             ]);
                         }
                    }
                  return ['status'=>true,'message'=>'Record found','data'=>$data];
          }
          return ['status'=>false,'message'=>'Record not found'];
    }
    
    public function addReview(Request $request){
         $rules = [
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'rating' => 'required',
            'review' => 'required',
         ];
       $validator = Validator::make($request->all(), $rules);
       if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
       }
       $rating = new Rating;
       $rating->user_id = $request->user_id;
       $rating->restaurant_id = $request->restaurant_id;
       $rating->rating = $request->rating;
       $rating->review = $request->review;
       if($rating->save())
           return ['status'=>true,'message'=>'Thank you for your feedback'];
       else
           return ['status'=>false,'message'=>'Failed to give feedback'];
    }

    public function updateReview(Request $request){
        $rules = [
           'review_id' => 'required|exists:rating_reviews,id',
           'rating' => 'required',
           'review' => 'required',
        ];
      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
          $errors =  $validator->errors()->all();
          return response(['status' => false , 'message' => $errors[0]] , 200);              
      }
      $rating = Rating::find($request->review_id);
      if($rating){
          $rating->rating = $request->rating;
          $rating->review = $request->review;
          if($rating->update())
              return ['status'=>true,'message'=>'Feedback updated successfully'];
          else
              return ['status'=>false,'message'=>'Failed to update your feedback'];
      }
          return ['status'=>false,'message'=>'Failed to update your feedback'];
    }

    
    public function deleteReview(Request $request){
        $rules = [
           'review_id' => 'required|exists:rating_reviews,id',
        ];
      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
          $errors =  $validator->errors()->all();
          return response(['status' => false , 'message' => $errors[0]] , 200);              
      }
      $rating = Rating::find($request->review_id);
      if($rating){
          if($rating->delete())
              return ['status'=>true,'message'=>'Successfully deleted your feedback'];
          else
              return ['status'=>false,'message'=>'Failed to delete'];
      }
      return ['status'=>false,'message'=>'Failed to delete'];
    }

    public function review(Request $request){
         $rules = [
            'user_id' => 'required',
            'restaurant_id' => 'required',
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
         }
         $rating = Rating::where('user_id',$request->user_id)->where('restaurant_id',$request->restaurant_id)->first();
         if($rating){
             $data = array();
             $data['user_id']    = $rating->user_id;
             $data['user_name']  = $rating->user->name;
             $data['user_profile_image'] = $rating->user->profile_image;
             $data['rating']     = $rating->rating;
             $data['review']     = $rating->review;
             $data['created_at'] = date('Y-m-d H:i:s');
             return ['status'=>true,'message'=>'Record found','data'=>$data];
         }
         return ['status'=>false,'message'=>'Record not found'];
    }

    public function addCart(Request $request){
        $rules = [
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'item_id'       => 'required|exists:items,id',
            'qty'           => 'required'
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
         }
         $isExist = Cart::where([
               'user_id'       =>$request->user_id,
               'restaurant_id' => $request->restaurant_id,
               'item_id'       => $request->item_id
               ])->count();
        if($isExist){
            return ['status'=>false,'message'=>'Already added to cart'];
        }
         $cart = new Cart;
         $cart->user_id = $request->user_id;
         $cart->restaurant_id = $request->restaurant_id;
         $cart->item_id = $request->item_id;
         if($cart->save())
             return ['status'=>true,'message'=>'Added to cart'];
        else 
            return ['status'=>false,'message'=>'Failed to add '];
    }

    public function removeCart(Request $request){
        $rules = [
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'item_id'       => 'required|exists:items,id',
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
         }
         $cartItem = Cart::where([
            'user_id'       =>$request->user_id,
            'restaurant_id' => $request->restaurant_id,
            'item_id'       => $request->item_id
            ])->first();
        if($cartItem->delete())
            return ['status'=>true,'message'=>'Item removed'];
        else
           return ['status'=>false,'message'=>'Failed to remove'];
    }

    public function updateCart(Request $request){
        $rules = [
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'item_id'       => 'required|exists:items,id',
            'qty'           => 'required'
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
         }
         $cart = Cart::where([
            'user_id'       =>$request->user_id,
            'restaurant_id' => $request->restaurant_id,
            'item_id'       => $request->item_id
            ])->first();
         if(empty($cart) || is_null($cart)){
             return ['status'=>false,'message'=>'Failed to update qty'];
         }
         $cart->qty = $request->qty;
         if($cart->save())
             return ['status'=>true,'message'=>'Qty updated'];
        else 
            return ['status'=>false,'message'=>'Failed to update qty '];
    }

    public function carts(Request $request){
        $rules = [
            'user_id' => 'required|exists:users,id'
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
         }       
         $carts = Cart::where('user_id',$request->user_id)->get();
         if($carts->toarray()){
              $data = array();
              $total = 0;
              foreach($carts as $key => $cart){
                  $total += $cart->subTotal();
                  array_push($data,[
                    'cart_item_id' => $cart->id,
                    'user_id'      => $cart->user_id,
                    'item_id'      => $cart->item_id,
                    'restaurant_id' => $cart->restaurant_id,
                    'item'          => $cart->item->title,
                    'description'   => $cart->item->description, 
                    'qty'           => $cart->qty,
                    'price'         => $cart->item->price, 
                    'price'         => $cart->item->image, 
                    'sub_total'     => $cart->subTotal(),
                  ]);
                }
                return ['status'=>true,'message'=>'Record found','total'=>number_format($total,2),'data'=>$data];
            }
            return ['status'=>false,'message'=>'Record not found'];
        }
        
        public function placeOrder(Request $request){
            $rules = [
                'user_id' => 'required|exists:users,id',
                'payment_type' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors =  $validator->errors()->all();
                return response(['status' => false , 'message' => $errors[0]] , 200);              
            }       
            $carts = Cart::where('user_id',$request->user_id)->get();
            if(empty($carts->toarray()) || is_null($carts->toarray())){
                return ['status'=>false,'message'=>'Cart is empty'];
            }
            $totalAmount = 0;
            if($carts->toarray()){
                foreach($carts as $key => $cart){
                  $totalAmount += $cart->qty * $cart->item->price;
                }
            }
            $storeOrderItemData = array();
            $storeOrderData = [
                'user_id' => $request->user_id,
                'restaurant_id'  => $carts[0]->restaurant_id,
                'order_date'     => date('Y-m-d H:i:s'),
                'order_status'   => '0',
                'payment_status' => '0' ,
                'delivery_type'  => $request->delivery_type,
                'payment_type'   => $request->payment_type,
                'total_amount'   => $totalAmount
            ];
         DB::beginTransaction();
         try{
             $orderId = DB::table('orders')->insertGetId($storeOrderData);
             foreach($carts as $key=> $cart){
                 array_push($storeOrderItemData,[
                     'order_id'  => $orderId,
                     'item_id'   => $cart->item_id,
                     'price'     => $cart->item->price,
                     'qty'       => $cart->qty
                 ]);
             }
             DB::table('order_items')->insert($storeOrderItemData);
             DB::commit();
             return ['status'=>true,'message'=>'Successfully placed order'];
         }catch(\Exception $e){
             DB::rollback();
             return ['status'=>false,'message'=>'Failed to place order'];
         }
    }

    public function orders(Request $request){
        $rules = [
            'user_id' => 'required|exists:users,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }       
        $orders = Order::where('user_id',$request->user_id)->orderBy('id','desc')->get();
        if($orders->toarray()){
            $data = array();
            foreach($orders as $key => $order){
               array_push($data,[
                 'order_id' => $order->id,
                 'user_id'  => $order->user_id,
                 'restaurant_id' => $order->restaurant_id,
                 'restaurant_name' => $order->restaurant->restaurant_name,
                 'order_status' => $order->order_status,
                 'payment_status' => $order->order_status,
                 'payment_type'  => $order->payment_type,
                 'delivery_type' => $order->delivery_type,
                 'order_date'    => date('Y-m-d H:i:s',strtotime($order->order_date)),
                 'total_amount'  => number_format($order->total_amount,2),
               ]);
                foreach($order->orederItems as $key2 => $orderItem){
                     $data[$key]['items'][$key2] = array(
                         'item_id' => $orderItem->id,
                         'name'    => $orderItem->item->title,
                         'description' => $orderItem->item->description,
                         'price' => number_format($orderItem->price,2),
                         'qty'   => $orderItem->qty,
                         'sub_total' => number_format($orderItem->qty*$orderItem->price,2)
                     );
                }
            }
            return ['status'=>true,'message'=>'Record found','data'=>$data];
        }
        return ['status'=>false,'message'=>'Record not found'];
    }

    public function orderDetails(Request $request){

            $rules = [
                'order_id' => 'required|exists:orders,id',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors =  $validator->errors()->all();
                return response(['status' => false , 'message' => $errors[0]] , 200);              
            }       

            $orders = Order::where('id',$request->order_id)->orderBy('id','desc')->get();

            if($orders->toarray()){
                $data = array();
                foreach($orders as $key => $order){
                array_push($data,[
                    'order_id' => $order->id,
                    'user_id'  => $order->user_id,
                    'restaurant_id' => $order->restaurant_id,
                    'restaurant_name' => $order->restaurant->restaurant_name,
                    'order_status' => $order->order_status,
                    'payment_status' => $order->order_status,
                    'payment_type'  => $order->payment_type,
                    'delivery_type' => $order->delivery_type,
                    'order_date'    => date('Y-m-d H:i:s',strtotime($order->order_date)),
                    'total_amount'  => number_format($order->total_amount,2),
                ]);

                    $data[$key]['user']['user_id']       = $order->user->id;
                    $data[$key]['user']['name']          = $order->user->name;
                    $data[$key]['user']['email']         = $order->user->email;
                    $data[$key]['user']['phone']         = $order->user->phone;
                    $data[$key]['user']['profile_image'] = $order->user->profile_image;

                    foreach($order->orederItems as $key2 => $orderItem){
                        $data[$key]['items'][$key2] = array(
                            'item_id' => $orderItem->id,
                            'name'    => $orderItem->item->title,
                            'description' => $orderItem->item->description,
                            'price' => number_format($orderItem->price,2),
                            'qty'   => $orderItem->qty,
                            'sub_total' => number_format($orderItem->qty*$orderItem->price,2)
                        );
                    }
                }
                $data = $data[0];
                return ['status'=>true,'message'=>'Record found','data'=>$data];
            }
            return ['status'=>false,'message'=>'Record not found'];       
    }

    public function cancelOrder(Request $request){
         $rules = [
            'order_id' => 'required|exists:orders,id',
            'user_id'  => 'required|'
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
         }       
         $order = Order::find($request->order_id);
         DB::beginTransaction();
         try{
             DB::table('orders')->where('id',$request->order_id)->update(['order_status'=>2]);
             DB::table('order_status')->insert(
                 [ 'order_id' => $request->order_id,'user_id' => $request->user_id , 'status' => '2' , 'comment' => $request->reason , 'change_by' => 'User' ]
                 );
             DB::commit();
             return ['status'=>true,'message'=>'Successfully cancelled'];
         }catch(\Exception $e){
             return ['status'=>false,'message'=>'Failed to cancel'];
         }
    }

    public function addWishlistItem(Request $request){
        $rules = [
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
         }
         $isExist = Wishlist::where([
               'user_id'    =>$request->user_id,
               'item_id'    => $request->item_id
               ])->count();
        if($isExist){
            return ['status'=>false,'message'=>'Already added to wishlist'];
        }
         $wishlist = new Wishlist;
         $wishlist->user_id = $request->user_id;
         $wishlist->item_id = $request->item_id;
         if($wishlist->save())
             return ['status'=>true,'message'=>'Added to wishlist'];
        else 
            return ['status'=>false,'message'=>'Failed to wishlist '];
    }

    public function removeWishlistItem(Request $request){
        $rules = [
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
         }
         $wishlist = Wishlist::where([
                    'user_id'       =>$request->user_id,
                    'item_id'       => $request->item_id
            ])->first();
        if($wishlist){
            if($wishlist->delete())
                return ['status'=>true,'message'=>'Item removed'];
        }
           return ['status'=>false,'message'=>'Failed to remove'];
    }

     public function wishlistItems(Request $request){
            $rules = [
                'user_id' => 'required|exists:users,id'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors =  $validator->errors()->all();
                return response(['status' => false , 'message' => $errors[0]] , 200);              
            }       
            $wishlists = Wishlist::where('user_id',$request->user_id)->get();
            if(empty($wishlists->toarray()) || is_null($wishlists->toarray())){
                return ['status'=>false,'message'=>'Wishlist is empty'];
            }
            if($wishlists->toArray()){
                $data = array();
                foreach($wishlists as $key => $wishlist){
                   array_push($data,[
                       'wishlist_id'   => $wishlist->id,
                       'restaurant_id' => $wishlist->item->restaurant_id,
                       'item_id'       => $wishlist->item->id,
                       'name'          => $wishlist->item->title,
                       'description'   => $wishlist->item->description,
                       'price'         => $wishlist->item->price
                   ]);
                }
                return ['status'=>true,'message'=>'Record found','data'=>$data];
            }
            return ['status'=>false,'message'=>'Record not found'];
     }

     public function wishlist(Request $request){
         $rules = [
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
           $errors =  $validator->errors()->all();
           return response(['status' => false , 'message' => $errors[0]] , 200);              
         }
         if($request->type == '1')
           return  $this->addWishlistItem($request);
         else
           return $this->removeWishlistItem($request);
     }
}