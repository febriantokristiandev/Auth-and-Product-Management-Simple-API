<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();
    
            if ($request->has('category')) {
                $query->where('category', $request->input('category'));
            }
    
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }
    
            $products = $query->paginate(10); 
    
            return response()->json($products);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Database query error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching products'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching product details'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create', Product::class);

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'category' => 'required|string',
                'image' => 'nullable|image',
            ]);

            $product = Product::create($request->all());

            return response()->json($product, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Database query error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating product'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $this->authorize('update', $product);

            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
                'category' => 'sometimes|required|string',
                'image' => 'nullable|image',
            ]);

            $product->update($request->all());

            return response()->json($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Database query error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating product'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            $this->authorize('delete', $product);

            $product->delete();

            return response()->json(['message' => 'Product Deleted']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Database query error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting product'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
