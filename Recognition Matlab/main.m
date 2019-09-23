function task6()

  mkdir('.\task6\out\');
  database_path = '.\task6\dataset';
  in_path = strcat(pwd, '\task6\in');
  max_dif = 10*200^3;

  [m,A,eigenfaces,pr_img] = eigenface_core(database_path);

  fid = fopen('.\task6\out\task6.txt', 'w');
  a = dir([in_path '\*.jpg']);
  a = size(a,1);
  
  for i = 1 : a   %loop through the images in 'in' folder
    image_path = strcat(in_path,'\',int2str(i),'.jpg');
    
    [min_dist,output_img_index] = face_recognition(image_path, m, A, eigenfaces, pr_img);
    fprintf(fid, "%f %d\n", round(min_dist), output_img_index);
    
    if (min_dist < max_dif)
        output_name = strcat(int2str(output_img_index),'.jpg');
        selected_image = imread(strcat(database_path,'\',output_name));
	
        test_image = imread(image_path);

        figure,imshow(test_image)
        title('Test Image');
        figure,imshow(selected_image);
        title('Equivalent Image');
        
        msg = strcat(strcat(int2str(i), '.jpg'), ', Matched image is:  ', output_name);
        disp(msg);
    elseif (min_dist < max_dif * 5 / 4)
        disp(strcat(strcat(int2str(i), '.jpg'), ', This image is a human face, but not a known one', '/n'));
    else
        disp(strcat(strcat(int2str(i), '.jpg'), ', This image is not a human face', '/n'));
    end
  end
  fclose(fid);
end


function [m,A,eigenfaces,pr_img] = eigenface_core(database_path)
  % M is the number of images in the database
  a = dir([database_path '\*.jpg']);
  M = size(a,1);
  T = [];
  for i = 1: M
      % column vector for the matrix image i
      Ti = double(rgb2gray(imread(strcat(database_path,'\',int2str(i),'.jpg'))))';
      Ti=Ti(:);
      T(1:numel(Ti),i)=Ti; %concatenate
  end

  m = mean(T, 2);
  A = T - m;

  [V D] = eig(A' * A); % eigenvectors and eigenvalues
  V(:, find(diag(D) > 1)); % eigenvectors with eigenvalues greater than 1

  eigenfaces = A * V;
  pr_img = eigenfaces' * A;
end

function [min_dist,output_img_index] = face_recognition(image_path, m, A, eigenfaces, pr_img)
  test_img = double(rgb2gray(imread(image_path)));
  test_img = test_img'; 
  test_img = test_img(:) - m; % test image column

  pr_test_img = eigenfaces' * test_img;
  dist = norm((pr_img - pr_test_img)');

  min_dist = min(dist);
  output_img_index = find(dist == min_dist);
end