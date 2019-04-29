@extends('admin.layouts.main')


@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Bookings list</li>
      </ol>
		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Bookings list</h2>
				<div class="filter">
					<select name="orderby" class="selectbox">
						<option value="Any status">Any status</option>
						<option value="Approved">Approved</option>
						<option value="Pending">Pending</option>
						<option value="Cancelled">Cancelled</option>
					</select>
				</div>
			</div>
			<div class="list_general">
				
				<table class="table">

					<thead>
						<tr>
							<th>asdf</th>
							<th>asdf</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>asdf</td>
							<td>asdf</td>
						</tr>
						<tr>
							<td>asdf</td>
							<td>asdf</td>
						</tr>
						<tr>
							<td>asdf</td>
							<td>asdf</td>
						</tr>
						<tr>
							<td>asdf</td>
							<td>asdf</td>
						</tr>
						<tr>
							<td>asdf</td>
							<td>asdf</td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
		<!-- /box_general-->
		<nav aria-label="...">
			<ul class="pagination pagination-sm add_bottom_30">
				<li class="page-item disabled">
					<a class="page-link" href="#" tabindex="-1">Previous</a>
				</li>
				<li class="page-item"><a class="page-link" href="#">1</a></li>
				<li class="page-item"><a class="page-link" href="#">2</a></li>
				<li class="page-item"><a class="page-link" href="#">3</a></li>
				<li class="page-item">
					<a class="page-link" href="#">Next</a>
				</li>
			</ul>
		</nav>
		<!-- /pagination-->

@endsection


