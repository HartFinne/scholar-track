from PIL import Image, ImageOps, ImageEnhance
import sys
import os

def split_and_enlarge(image_path, enlargement_factor=2, output_dir="."):
    """
    Splits an image into four equal parts, enlarges them, and saves the results.
    Returns paths to the saved parts.
    """
    image = Image.open(image_path)
    width, height = image.size

    # Calculate midpoints for splitting
    mid_x = width // 2
    mid_y = height // 2

    # Split the image into four parts
    top_left = image.crop((0, 0, mid_x, mid_y))
    top_right = image.crop((mid_x, 0, width, mid_y))
    bottom_left = image.crop((0, mid_y, mid_x, height))
    bottom_right = image.crop((mid_x, mid_y, width, height))

    # Enlarge each part
    top_left_enlarged = top_left.resize((mid_x * enlargement_factor, mid_y * enlargement_factor))
    top_right_enlarged = top_right.resize((mid_x * enlargement_factor, mid_y * enlargement_factor))
    bottom_left_enlarged = bottom_left.resize((mid_x * enlargement_factor, mid_y * enlargement_factor))
    bottom_right_enlarged = bottom_right.resize((mid_x * enlargement_factor, mid_y * enlargement_factor))

    # Save the parts
    base_name, ext = os.path.splitext(os.path.basename(image_path))
    top_left_path = os.path.join(output_dir, f"{base_name}_top_left{ext}")
    top_right_path = os.path.join(output_dir, f"{base_name}_top_right{ext}")
    bottom_left_path = os.path.join(output_dir, f"{base_name}_bottom_left{ext}")
    bottom_right_path = os.path.join(output_dir, f"{base_name}_bottom_right{ext}")
    
    top_left_enlarged.save(top_left_path)
    top_right_enlarged.save(top_right_path)
    bottom_left_enlarged.save(bottom_left_path)
    bottom_right_enlarged.save(bottom_right_path)

    return top_left_path, top_right_path, bottom_left_path, bottom_right_path


def process_image_for_ocr(image_path):
    """
    Applies preprocessing to an image for better OCR results using Pillow.
    """
    # Open the image
    image = Image.open(image_path).convert("L")  # Convert to grayscale

    # Increase contrast
    enhancer = ImageEnhance.Contrast(image)
    image = enhancer.enhance(2.0)

    # Binarize the image
    threshold = 128
    image = image.point(lambda p: 255 if p > threshold else 0)

    # Save the processed image
    base_name, ext = os.path.splitext(image_path)
    processed_path = f"{base_name}_processed{ext}"
    image.save(processed_path)

    return processed_path

def main():
    """
    Main script for preprocessing an image, splitting it, and saving processed parts.
    """
    if len(sys.argv) < 2:
        print("Usage: python ocr_script.py <image_path>")
        sys.exit(1)

    image_path = sys.argv[1]

    # Define output directory (same as input image's directory)
    output_dir = os.path.dirname(image_path)

    # Split and enlarge the image
    part_paths = split_and_enlarge(image_path, enlargement_factor=2, output_dir=output_dir)

    # Process each part for OCR
    processed_paths = []
    for part_path in part_paths:
        processed_path = process_image_for_ocr(part_path)
        processed_paths.append(processed_path)

    # Output paths to processed images
    for processed_path in processed_paths:
        print(processed_path)



if __name__ == "__main__":
    main()

